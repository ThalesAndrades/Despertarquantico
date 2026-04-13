<?php
/**
 * Despertar Espiral — Installer (one-shot)
 *
 * This script is a safety net for the very first boot on a fresh server.
 * In normal operation, Database.php auto-bootstraps the schema on the
 * first connection, so this file should only be touched if you need a
 * visual status check.
 *
 * PRODUCTION GUARD:
 *  - Refuses to run if APP_ENV=production
 *  - Refuses to run if the users table already exists
 *  - Requires a .env file at the project root (with DB_* keys set)
 *
 * After the first successful boot, DELETE this file from the server.
 */

require_once __DIR__ . '/src/Env.php';
$envPath = __DIR__ . '/.env';
if (!is_file($envPath)) {
    // Mesmo comportamento do front controller: permitir .env fora do public_html.
    $parentEnv = dirname(__DIR__) . '/.env';
    if (is_file($parentEnv)) {
        $envPath = $parentEnv;
    }
}
Env::load($envPath);

$appEnv = Env::get('APP_ENV', 'production');
$installToken = (string) Env::get('INSTALL_TOKEN', '');
$providedToken = (string) ($_GET['token'] ?? '');

// Optional hard guard (recommended): if INSTALL_TOKEN is set, require it in the URL.
if ($installToken !== '' && !hash_equals($installToken, $providedToken)) {
    http_response_code(404);
    exit;
}

header('Content-Type: text/html; charset=UTF-8');
echo '<!doctype html><html lang="pt-BR"><head><meta charset="UTF-8">';
echo '<title>Instalador - Despertar Espiral</title>';
echo '<style>body{font-family:Arial,sans-serif;max-width:720px;margin:40px auto;padding:20px;background:#0A0A0A;color:#eee}';
echo 'h1{color:#C9A84C}h2{color:#C9A84C}.ok{color:#6ee7b7}.err{color:#fca5a5}';
echo 'pre{background:#111;padding:16px;border-radius:8px;overflow-x:auto;border:1px solid #222}';
echo 'code{background:#111;padding:2px 6px;border-radius:4px}</style></head><body>';
echo '<h1>Instalador - Despertar Espiral</h1>';

// Production guard
if (strtolower($appEnv) === 'production') {
    http_response_code(403);
    echo '<p class="err">Instalador desativado em producao. Em producao, use o primeiro acesso normal da aplicacao (o schema e criado automaticamente) e mantenha este arquivo bloqueado/removido.</p>';
    echo '</body></html>';
    exit;
}

// Credentials must live in .env
$dbHost = Env::get('DB_HOST', '');
$dbName = Env::get('DB_NAME', '');
$dbUser = Env::get('DB_USER', '');
$dbPass = Env::get('DB_PASS', '');

if ($dbHost === '' || $dbName === '' || $dbUser === '') {
    http_response_code(500);
    echo '<p class="err">.env incompleto. Defina DB_HOST, DB_NAME, DB_USER e DB_PASS antes de rodar o instalador.</p>';
    echo '<p>Consulte <code>.env.example</code> na raiz do projeto.</p>';
    echo '</body></html>';
    exit;
}

// 1. Connect
try {
    $dsn = "mysql:host={$dbHost};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo '<p class="ok">[OK] Conexao MySQL estabelecida</p>';
} catch (PDOException $e) {
    echo '<p class="err">[ERR] Conexao: ' . htmlspecialchars((string) $e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p></body></html>';
    exit;
}

// 2. Create database if missing
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$dbName}`");
    echo '<p class="ok">[OK] Banco "' . htmlspecialchars($dbName) . '" pronto</p>';
} catch (PDOException $e) {
    echo '<p class="err">[ERR] Banco: ' . htmlspecialchars((string) $e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p></body></html>';
    exit;
}

// 3. Guard against re-install
$usersExists = $pdo->query("SHOW TABLES LIKE 'users'")->fetch();
if ($usersExists) {
    echo '<p class="err">[ABORT] A tabela <code>users</code> ja existe. O instalador nao roda em bancos populados.</p>';
    echo '<p>Para atualizar o schema de um banco existente, basta acessar qualquer rota da aplicacao — as migracoes idempotentes em Database.php sao aplicadas automaticamente.</p>';
    echo '<p class="err" style="margin-top:20px;font-weight:bold;">EXCLUA ESTE ARQUIVO (install.php) do servidor.</p>';
    echo '</body></html>';
    exit;
}

// 4. Run schema.sql
$schemaFile = __DIR__ . '/database/schema.sql';
if (!file_exists($schemaFile)) {
    echo '<p class="err">[ERR] database/schema.sql ausente. Verifique o deploy.</p></body></html>';
    exit;
}

$schema = file_get_contents($schemaFile);
$schema = preg_replace('/CREATE DATABASE.*?;/s', '', $schema);
$schema = preg_replace('/USE .*?;/s', '', $schema);
$schema = preg_replace('/INSERT INTO users.*?;/s', '', $schema);

$statements = array_filter(array_map('trim', explode(';', $schema)));
$tableCount = 0;
foreach ($statements as $stmt) {
    if (empty($stmt) || $stmt === '--') continue;
    try {
        $pdo->exec($stmt);
        if (stripos($stmt, 'CREATE TABLE') !== false) {
            $tableCount++;
        }
    } catch (PDOException $e) {
        if ($e->getCode() !== '42S01') {
            echo '<p class="err">[WARN] SQL: ' . htmlspecialchars((string) $e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
        }
    }
}
echo "<p class='ok'>[OK] Schema executado ({$tableCount} tabelas)</p>";

// 5. Seed admin from .env
$adminEmail = strtolower(trim(Env::get('ADMIN_INIT_EMAIL', 'sunyan@despertarespiral.com')));
$adminPass = Env::get('ADMIN_INIT_PASSWORD', '');
$adminName = Env::get('ADMIN_INIT_NAME', 'Sunyan Nunes');

if ($adminPass === '') {
    echo '<p class="err">[SKIP] ADMIN_INIT_PASSWORD nao definido em .env — admin nao criada. Defina a senha no .env e recarregue esta pagina para criar a conta admin.</p>';
} else {
    try {
        $hash = password_hash($adminPass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("SELECT id FROM users WHERE LOWER(email) = ?");
        $stmt->execute([$adminEmail]);
        if ($stmt->fetch()) {
            echo '<p class="ok">[OK] Admin ja existente: ' . htmlspecialchars($adminEmail) . '</p>';
        } else {
            $pdo->prepare("INSERT INTO users (name, email, password_hash, anonymous_name, role) VALUES (?,?,?,?,?)")
                ->execute([$adminName, $adminEmail, $hash, 'Sunyan', 'admin']);
            echo '<p class="ok">[OK] Admin criada: ' . htmlspecialchars($adminEmail, ENT_QUOTES, 'UTF-8') . '</p>';
        }
    } catch (PDOException $e) {
        echo '<p class="err">[ERR] Admin: ' . htmlspecialchars((string) $e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
    }
}

// 6. Ensure upload/log directories
foreach (['uploads/products', 'uploads/content', 'storage/logs'] as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (!is_dir($path)) {
        @mkdir($path, 0755, true);
    }
    if (is_writable($path)) {
        echo '<p class="ok">[OK] Pasta ' . htmlspecialchars($dir) . ' com permissao de escrita</p>';
    } else {
        echo '<p class="err">[WARN] Pasta ' . htmlspecialchars($dir) . ' sem permissao de escrita (chmod 755)</p>';
    }
}

echo '<hr style="border-color:#222;margin:30px 0">';
echo '<h2>Instalacao concluida</h2>';
echo '<ol>';
echo '<li>Acesse <a href="/" style="color:#C9A84C">despertarespiral.com</a> para conferir a landing</li>';
echo '<li>Faca login em <code>/login</code> com a admin definida no .env</li>';
echo '<li>Troque a senha da admin apos o primeiro login</li>';
echo '<li>Configure o webhook Asaas apontando para <code>' . (Env::get('APP_URL', 'https://despertarespiral.com')) . '/webhook/asaas</code> com o mesmo <code>ASAAS_WEBHOOK_TOKEN</code> do .env</li>';
echo '<li>Verifique no Sequenzy que eventos estao chegando na webhook URL configurada</li>';
echo '<li class="err" style="font-weight:bold;margin-top:12px">EXCLUA ESTE ARQUIVO (install.php) do servidor por seguranca</li>';
echo '</ol>';
echo '</body></html>';
