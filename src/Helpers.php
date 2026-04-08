<?php
/**
 * Helper Functions
 */

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . APP_URL . '/' . ltrim($path, '/'));
    exit;
}

function url(string $path = ''): string
{
    return APP_URL . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return APP_URL . '/public/' . ltrim($path, '/');
}

function old(string $key, string $default = ''): string
{
    return e($_SESSION['old_input'][$key] ?? $default);
}

function flash(string $key, ?string $value = null): ?string
{
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
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > MAX_UPLOAD_SIZE) {
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!in_array($mime, ALLOWED_IMAGE_TYPES)) {
        return null;
    }

    $ext = match ($mime) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        default => 'jpg',
    };

    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $path = UPLOADS_PATH . '/' . $directory;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $path . '/' . $filename)) {
        return $directory . '/' . $filename;
    }
    return null;
}

function closeLayout(): void
{
    echo '</div><script src="' . asset('js/app.js') . '"></script></main></body></html>';
}
