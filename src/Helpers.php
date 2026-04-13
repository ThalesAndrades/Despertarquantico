<?php
/**
 * Helper Functions
 */

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function env(string $key, $default = null)
{
    return Env::get($key, $default);
}

function env_bool(string $key, bool $default = false): bool
{
    $value = Env::get($key, null);
    if ($value === null) {
        return $default;
    }
    $normalized = strtolower(trim((string) $value));
    return in_array($normalized, ['1', 'true', 'yes', 'on'], true);
}

function env_int(string $key, int $default = 0): int
{
    $value = Env::get($key, null);
    if ($value === null || $value === '') {
        return $default;
    }
    return (int) $value;
}

function redirect(string $path): void
{
    header('Location: ' . APP_URL . '/' . ltrim($path, '/'));
    exit;
}

function ensureSessionStarted(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function closeSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
    }
}

function url(string $path = ''): string
{
    return APP_URL . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    $relativePath = 'public/' . ltrim($path, '/');
    $absolutePath = BASE_PATH . '/' . $relativePath;
    $version = is_file($absolutePath) ? filemtime($absolutePath) : time();

    return APP_URL . '/' . $relativePath . '?v=' . $version;
}

function themeInitScript(): string
{
    return <<<HTML
<script>
(function () {
    try {
        var storageKey = 'mulher-espiral-theme-override';
        var legacyModeKey = 'mulher-espiral-theme-mode';
        var legacyKey = 'mulher-espiral-theme';
        var override = localStorage.getItem(storageKey) || localStorage.getItem(legacyModeKey) || localStorage.getItem(legacyKey) || '';
        if (override !== 'light' && override !== 'dark') override = '';
        var hour = (new Date()).getHours();
        var theme = override !== '' ? override : ((hour >= 6 && hour < 19) ? 'light' : 'dark');
        document.documentElement.setAttribute('data-theme', theme);
        document.documentElement.setAttribute('data-theme-mode', override !== '' ? 'manual' : 'auto');
        document.documentElement.style.colorScheme = theme;
    } catch (error) {
        document.documentElement.setAttribute('data-theme', 'dark');
        document.documentElement.setAttribute('data-theme-mode', 'auto');
        document.documentElement.style.colorScheme = 'dark';
    }
})();
</script>
HTML;
}

function themeScriptTag(): string
{
    return '<script src="' . asset('js/theme.js') . '" defer></script>';
}

function themeToggleButton(string $classes = 'theme-toggle', string $label = 'Tema'): string
{
    return '<button type="button" class="' . e($classes) . '" data-theme-toggle aria-label="Alternar tema" aria-pressed="false">'
        . '<span class="theme-toggle-icon" aria-hidden="true"></span>'
        . '<span class="theme-toggle-text">' . e($label) . '</span>'
        . '</button>';
}

function old(string $key, string $default = ''): string
{
    ensureSessionStarted();
    return e($_SESSION['old_input'][$key] ?? $default);
}

function flash(string $key, ?string $value = null): ?string
{
    ensureSessionStarted();

    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function isLoggedIn(): bool
{
    ensureSessionStarted();
    return isset($_SESSION['user_id']);
}

function requireAuth(): void
{
    if (!isLoggedIn()) {
        flash('error', 'Você precisa fazer login para acessar esta página.');
        redirect('login');
    }
}

function requireAdmin(): void
{
    requireAuth();
    if (($_SESSION['user_role'] ?? '') !== 'admin') {
        http_response_code(403);
        die('Acesso negado.');
    }
}

function currentUser(): ?array
{
    ensureSessionStarted();

    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
        'anonymous_name' => $_SESSION['user_anonymous_name'] ?? '',
        'role' => $_SESSION['user_role'],
    ];
}

function view(string $template, array $data = []): void
{
    extract($data);
    require VIEWS_PATH . '/' . $template . '.php';
}

function timeAgo(string $datetime): string
{
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) return $diff->y . ' ano' . ($diff->y > 1 ? 's' : '') . ' atrás';
    if ($diff->m > 0) return $diff->m . ' mês' . ($diff->m > 1 ? 'es' : '') . ' atrás';
    if ($diff->d > 0) return $diff->d . ' dia' . ($diff->d > 1 ? 's' : '') . ' atrás';
    if ($diff->h > 0) return $diff->h . 'h atrás';
    if ($diff->i > 0) return $diff->i . 'min atrás';
    return 'agora';
}

function slugify(string $text): string
{
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[áàãâä]/u', 'a', $text);
    $text = preg_replace('/[éèêë]/u', 'e', $text);
    $text = preg_replace('/[íìîï]/u', 'i', $text);
    $text = preg_replace('/[óòõôö]/u', 'o', $text);
    $text = preg_replace('/[úùûü]/u', 'u', $text);
    $text = preg_replace('/[ç]/u', 'c', $text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

function uploadImage(array $file, string $directory): ?string
{
    $directory = trim($directory, '/');
    if ($directory === '' || strpos($directory, '..') !== false) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > MAX_UPLOAD_SIZE) {
        return null;
    }
    if (empty($file['tmp_name']) || !is_file($file['tmp_name'])) {
        return null;
    }
    if (!is_uploaded_file($file['tmp_name'])) {
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!in_array($mime, ALLOWED_IMAGE_TYPES)) {
        return null;
    }
    // Extra validation: ensure it's a readable image and the declared mime matches.
    $imgInfo = @getimagesize($file['tmp_name']);
    if ($imgInfo === false || empty($imgInfo['mime']) || $imgInfo['mime'] !== $mime) {
        return null;
    }

    $ext = 'jpg';
    if ($mime === 'image/png') {
        $ext = 'png';
    } elseif ($mime === 'image/webp') {
        $ext = 'webp';
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $path = UPLOADS_PATH . '/' . $directory;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $path . '/' . $filename)) {
        @chmod($path . '/' . $filename, 0644);
        return $directory . '/' . $filename;
    }
    return null;
}

function closeLayout(): void
{
    echo '</div>' . themeScriptTag() . '<script src="' . asset('js/app.js') . '" defer></script></main></body></html>';
}
