<?php
/**
 * Instalador da Plataforma Mulher Espiral
 *
 * Acesse este arquivo pelo navegador DEPOIS de:
 * 1. Fazer upload do ZIP via File Manager
 * 2. Extrair o ZIP na pasta public_html
 * 3. Criar o banco de dados MySQL no painel Hostinger
 * 4. Preencher as credenciais abaixo
 *
 * EXCLUA ESTE ARQUIVO após a instalação!
 */

// ============================================
// PREENCHA ESTAS CREDENCIAIS
// ============================================
$DB_HOST     = 'localhost';      // Geralmente localhost na Hostinger
$DB_NAME     = '';               // Nome do banco criado no painel
$DB_USER     = '';               // Usuário do banco
$DB_PASS     = '';               // Senha do banco

$ADMIN_NAME  = 'Sunyan Nunes';
$ADMIN_EMAIL = 'sunyan@mulherespiral.shop';  // Email da admin
$ADMIN_PASS  = '';               // Senha que a Sunyan vai usar
// ============================================

$errors = [];
$success = [];

// Validar preenchimento
if (empty($DB_NAME) || empty($DB_USER) || empty($ADMIN_EMAIL) || empty($ADMIN_PASS)) {
    die('<h1>Instalador Mulher Espiral</h1>
        <p style="color:red;">Abra este arquivo (install.php) e preencha as credenciais do banco de dados e da admin antes de acessar pelo navegador.</p>
        <pre>
$DB_HOST     = "localhost";
$DB_NAME     = "seu_banco_aqui";
$DB_USER     = "seu_usuario_aqui";
$DB_PASS     = "sua_senha_aqui";
$ADMIN_EMAIL = "email@admin.com";
$ADMIN_PASS  = "senha_da_admin";
        </pre>');
}

echo '<html><head><title>Instalador - Mulher Espiral</title>
<style>body{font-family:Arial,sans-serif;max-width:700px;margin:50px auto;padding:20px;}
h1{color:#6B21A8;} .ok{color:green;} .err{color:red;} pre{background:#f5f5f5;padding:16px;border-radius:8px;overflow-x:auto;}</style>
</head><body>';
echo '<h1>Instalador - Mulher Espiral</h1>';

// 1. Testar conexão com banco
try {
    $dsn = "mysql:host=$DB_HOST;charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo '<p class="ok">✓ Conexão com MySQL estabelecida</p>';
} catch (PDOException $e) {
    die('<p class="err">✗ Erro de conexão: ' . htmlspecialchars($e->getMessage()) . '</p></body></html>');
}

// 2. Criar banco se não existir
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$DB_NAME`");
    echo '<p class="ok">✓ Banco de dados "' . htmlspecialchars($DB_NAME) . '" pronto</p>';
} catch (PDOException $e) {
    die('<p class="err">✗ Erro ao criar banco: ' . htmlspecialchars($e->getMessage()) . '</p></body></html>');
}

// 3. Executar schema
$schemaFile = __DIR__ . '/database/schema.sql';
if (!file_exists($schemaFile)) {
    die('<p class="err">✗ Arquivo database/schema.sql não encontrado. Verifique se extraiu o ZIP corretamente.</p></body></html>');
}

$schema = file_get_contents($schemaFile);
// Remove CREATE DATABASE and USE statements (already handled above)
$schema = preg_replace('/CREATE DATABASE.*?;/s', '', $schema);
$schema = preg_replace('/USE .*?;/s', '', $schema);
// Remove the default INSERT (we'll create our own admin)
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
        // Ignore "table already exists" errors
        if ($e->getCode() !== '42S01') {
            echo '<p class="err">⚠ SQL Warning: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}
echo "<p class='ok'>✓ Schema executado ($tableCount tabelas criadas)</p>";

// 4. Criar admin
$hash = password_hash($ADMIN_PASS, PASSWORD_DEFAULT);
try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$ADMIN_EMAIL]);
    if ($stmt->fetch()) {
        $pdo->prepare("UPDATE users SET name=?, password_hash=?, anonymous_name='Sunyan', role='admin' WHERE email=?")
            ->execute([$ADMIN_NAME, $hash, $ADMIN_EMAIL]);
        echo '<p class="ok">✓ Admin atualizada: ' . htmlspecialchars($ADMIN_EMAIL) . '</p>';
    } else {
        $pdo->prepare("INSERT INTO users (name, email, password_hash, anonymous_name, role) VALUES (?,?,?,?,?)")
            ->execute([$ADMIN_NAME, $ADMIN_EMAIL, $hash, 'Sunyan', 'admin']);
        echo '<p class="ok">✓ Admin criada: ' . htmlspecialchars($ADMIN_EMAIL) . '</p>';
    }
} catch (PDOException $e) {
    echo '<p class="err">✗ Erro ao criar admin: ' . htmlspecialchars($e->getMessage()) . '</p>';
}

// 5. Atualizar config/database.php
$configDb = "<?php
return [
    'host' => '$DB_HOST',
    'dbname' => '$DB_NAME',
    'username' => '$DB_USER',
    'password' => '$DB_PASS',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
";
if (file_put_contents(__DIR__ . '/config/database.php', $configDb)) {
    echo '<p class="ok">✓ config/database.php atualizado com credenciais</p>';
} else {
    echo '<p class="err">✗ Não conseguiu escrever config/database.php (verifique permissões)</p>';
}

// 6. Verificar permissões de uploads
foreach (['uploads/products', 'uploads/content'] as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
    if (is_writable($path)) {
        echo '<p class="ok">✓ Pasta ' . $dir . ' com permissão de escrita</p>';
    } else {
        echo '<p class="err">✗ Pasta ' . $dir . ' sem permissão de escrita (chmod 755)</p>';
    }
}

// 7. Verificar .htaccess
if (file_exists(__DIR__ . '/.htaccess')) {
    echo '<p class="ok">✓ .htaccess presente</p>';
} else {
    echo '<p class="err">✗ .htaccess não encontrado!</p>';
}

// 8. Verificar config/app.php
if (file_exists(__DIR__ . '/config/app.php')) {
    echo '<p class="ok">✓ config/app.php presente</p>';
} else {
    echo '<p class="err">✗ config/app.php não encontrado!</p>';
}

echo '<hr>';
echo '<h2 style="color:#065F46;">Instalação concluída!</h2>';
echo '<p><strong>Próximos passos:</strong></p>';
echo '<ol>';
echo '<li>Acesse <a href="/" target="_blank">mulherespiral.shop</a> para ver a landing page</li>';
echo '<li>Acesse <a href="/login" target="_blank">mulherespiral.shop/login</a> para fazer login como admin</li>';
echo '<li>Acesse <a href="/admin" target="_blank">mulherespiral.shop/admin</a> para configurar produtos</li>';
echo '<li>Configure as chaves Stripe em <code>config/stripe.php</code></li>';
echo '<li>Configure o SMTP em <code>config/mail.php</code></li>';
echo '<li style="color:red;font-weight:bold;">EXCLUA ESTE ARQUIVO (install.php) por segurança!</li>';
echo '</ol>';
echo '<pre>';
echo "Login admin: $ADMIN_EMAIL\n";
echo "Senha: (a que você definiu acima)\n";
echo '</pre>';
echo '</body></html>';
