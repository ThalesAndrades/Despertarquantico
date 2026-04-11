<?php
/**
 * Database Singleton - PDO Wrapper
 *
 * Auto-creates the schema on the first connection if tables don't exist,
 * then runs idempotent structural migrations (Stripe → Asaas rename,
 * new columns, missing indexes) on every boot. All migrations are safe
 * to re-run — they check for existing columns/indexes before altering.
 */
class Database
{
    private static ?PDO $instance = null;
    private static bool $migrated = false;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require BASE_PATH . '/config/database.php';
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            self::$instance = new PDO($dsn, $config['username'], $config['password'], $config['options']);

            if (!self::$migrated) {
                self::bootstrapSchema();
                self::applyIncrementalMigrations();
                self::$migrated = true;
            }
        }
        return self::$instance;
    }

    /**
     * Run the full schema.sql only when the database is empty (no `users` table).
     * On an already-populated database this is a no-op.
     */
    private static function bootstrapSchema(): void
    {
        $pdo = self::$instance;

        $result = $pdo->query("SHOW TABLES LIKE 'users'")->fetch();
        if ($result) {
            return;
        }

        $schemaFile = BASE_PATH . '/database/schema.sql';
        if (!file_exists($schemaFile)) {
            return;
        }

        $schema = file_get_contents($schemaFile);
        $schema = preg_replace('/CREATE DATABASE.*?;/s', '', $schema);
        $schema = preg_replace('/USE .*?;/s', '', $schema);
        $schema = preg_replace('/INSERT INTO users.*?;/s', '', $schema);

        $statements = array_filter(array_map('trim', explode(';', $schema)));
        foreach ($statements as $stmt) {
            if (empty($stmt) || $stmt === '--') {
                continue;
            }
            try {
                $pdo->exec($stmt);
            } catch (PDOException $e) {
                if ($e->getCode() !== '42S01') {
                    error_log('Bootstrap schema error: ' . $e->getMessage());
                }
            }
        }

        // Optional: seed initial admin from env (never commit a default password)
        $adminEmail = Env::get('ADMIN_INIT_EMAIL', 'sunyan@despertarespiral.com');
        $adminPassword = Env::get('ADMIN_INIT_PASSWORD', '');
        if ($adminPassword !== '') {
            $existing = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $existing->execute([$adminEmail]);
            if (!$existing->fetch()) {
                $hash = password_hash($adminPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare(
                    "INSERT INTO users (name, email, password_hash, anonymous_name, role) VALUES (?, ?, ?, ?, ?)"
                );
                $stmt->execute(['Sunyan Nunes', $adminEmail, $hash, 'Sunyan', 'admin']);
            }
        }

        error_log('Bootstrap schema completed successfully.');
    }

    /**
     * Idempotent structural changes that must be applied to already-deployed
     * databases. Each helper checks for the current state before mutating.
     */
    private static function applyIncrementalMigrations(): void
    {
        $pdo = self::$instance;

        try {
            // orders: rename Stripe columns to Asaas equivalents
            if (self::columnExists('orders', 'stripe_session_id') && !self::columnExists('orders', 'asaas_payment_id')) {
                $pdo->exec("ALTER TABLE orders CHANGE COLUMN stripe_session_id asaas_payment_id VARCHAR(60) NOT NULL");
            }
            if (self::columnExists('orders', 'stripe_payment_intent') && !self::columnExists('orders', 'asaas_invoice_url')) {
                $pdo->exec("ALTER TABLE orders CHANGE COLUMN stripe_payment_intent asaas_invoice_url VARCHAR(500) DEFAULT NULL");
            }
            if (!self::columnExists('orders', 'asaas_event')) {
                $pdo->exec("ALTER TABLE orders ADD COLUMN asaas_event VARCHAR(50) DEFAULT NULL AFTER asaas_invoice_url");
            }
            if (!self::columnExists('orders', 'payment_method')) {
                $pdo->exec("ALTER TABLE orders ADD COLUMN payment_method ENUM('pix', 'credit_card', 'boleto', 'undefined') DEFAULT 'undefined' AFTER asaas_event");
            }

            // products: drop unused stripe_price_id if it still exists
            if (self::columnExists('products', 'stripe_price_id')) {
                $pdo->exec("ALTER TABLE products DROP COLUMN stripe_price_id");
            }

            // users: Asaas customer id for checkout reuse
            if (!self::columnExists('users', 'asaas_customer_id')) {
                $pdo->exec("ALTER TABLE users ADD COLUMN asaas_customer_id VARCHAR(60) DEFAULT NULL AFTER reset_expires");
            }

            // Indexes (all idempotent via SHOW INDEX check)
            self::ensureIndex('orders', 'idx_asaas_payment', 'asaas_payment_id');
            self::ensureIndex('orders', 'idx_user_status', 'user_id, status');
            self::ensureIndex('users', 'idx_asaas_customer', 'asaas_customer_id');
            self::ensureIndex('modules', 'idx_modules_product', 'product_id');
            self::ensureIndex('lessons', 'idx_lessons_module', 'module_id');
            self::ensureIndex('user_products', 'idx_user_products_user', 'user_id');

            // Login rate-limit table (new in hardening phase)
            self::ensureTable('login_attempts', "
                CREATE TABLE login_attempts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    ip_address VARCHAR(45) NOT NULL,
                    email VARCHAR(150) DEFAULT NULL,
                    attempted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_ip_time (ip_address, attempted_at),
                    INDEX idx_email_time (email, attempted_at)
                ) ENGINE=InnoDB
            ");

            self::ensureTable('high_ticket_applications', "
                CREATE TABLE high_ticket_applications (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(120) NOT NULL,
                    email VARCHAR(150) NOT NULL,
                    whatsapp VARCHAR(40) NOT NULL,
                    moment TEXT NOT NULL,
                    goal TEXT NOT NULL,
                    status ENUM('new', 'contacted', 'qualified', 'unqualified') DEFAULT 'new',
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_email_created (email, created_at),
                    INDEX idx_status_created (status, created_at)
                ) ENGINE=InnoDB
            ");

            // orders: drop legacy idx_session if it points to the renamed column
            self::dropIndexIfExists('orders', 'idx_session');
        } catch (PDOException $e) {
            error_log('Incremental migration error: ' . $e->getMessage());
        }
    }

    private static function columnExists(string $table, string $column): bool
    {
        $stmt = self::$instance->prepare(
            "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?"
        );
        $stmt->execute([$table, $column]);
        return (bool) $stmt->fetchColumn();
    }

    private static function indexExists(string $table, string $indexName): bool
    {
        $stmt = self::$instance->prepare(
            "SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?"
        );
        $stmt->execute([$table, $indexName]);
        return (bool) $stmt->fetchColumn();
    }

    private static function tableExists(string $table): bool
    {
        $stmt = self::$instance->prepare(
            "SELECT 1 FROM INFORMATION_SCHEMA.TABLES
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?"
        );
        $stmt->execute([$table]);
        return (bool) $stmt->fetchColumn();
    }

    private static function ensureIndex(string $table, string $indexName, string $columns): void
    {
        if (!self::tableExists($table) || self::indexExists($table, $indexName)) {
            return;
        }
        self::$instance->exec("CREATE INDEX {$indexName} ON {$table} ({$columns})");
    }

    private static function dropIndexIfExists(string $table, string $indexName): void
    {
        if (self::tableExists($table) && self::indexExists($table, $indexName)) {
            self::$instance->exec("DROP INDEX {$indexName} ON {$table}");
        }
    }

    private static function ensureTable(string $table, string $createSql): void
    {
        if (!self::tableExists($table)) {
            self::$instance->exec($createSql);
        }
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $result = self::query($sql, $params)->fetch();
        return $result ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function insert(string $sql, array $params = []): string
    {
        self::query($sql, $params);
        return self::getInstance()->lastInsertId();
    }

    public static function count(string $sql, array $params = []): int
    {
        return (int) self::query($sql, $params)->fetchColumn();
    }
}
