<?php
/**
 * Database Singleton - PDO Wrapper
 * Auto-migrates on first connection if tables don't exist
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
                self::autoMigrate();
                self::$migrated = true;
            }
        }
        return self::$instance;
    }

    private static function autoMigrate(): void
    {
        $pdo = self::$instance;

        // Check if users table exists
        $result = $pdo->query("SHOW TABLES LIKE 'users'")->fetch();
        if ($result) {
            return; // Tables already exist
        }

        // Run schema
        $schemaFile = BASE_PATH . '/database/schema.sql';
        if (!file_exists($schemaFile)) {
            return;
        }

        $schema = file_get_contents($schemaFile);
        // Remove CREATE DATABASE and USE statements
        $schema = preg_replace('/CREATE DATABASE.*?;/s', '', $schema);
        $schema = preg_replace('/USE .*?;/s', '', $schema);
        // Remove default INSERT
        $schema = preg_replace('/INSERT INTO users.*?;/s', '', $schema);

        $statements = array_filter(array_map('trim', explode(';', $schema)));
        foreach ($statements as $stmt) {
            if (empty($stmt) || $stmt === '--') continue;
            try {
                $pdo->exec($stmt);
            } catch (PDOException $e) {
                if ($e->getCode() !== '42S01') {
                    error_log('Auto-migrate error: ' . $e->getMessage());
                }
            }
        }

        // Create admin user
        $adminEmail = 'sunyan@mulherespiral.shop';
        $existing = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $existing->execute([$adminEmail]);

        if (!$existing->fetch()) {
            $hash = password_hash('@Telemed123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password_hash, anonymous_name, role) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute(['Sunyan Nunes', $adminEmail, $hash, 'Sunyan', 'admin']);
        }

        error_log('Auto-migration completed successfully.');
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
