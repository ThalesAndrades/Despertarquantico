<?php
/**
 * CSRF Protection
 */
class CSRF
{
    public static function generate(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function field(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    public static function verify(): bool
    {
        $token = $_POST['csrf_token'] ?? '';
        return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    public static function check(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !self::verify()) {
            http_response_code(403);
            die('Token de segurança inválido. Por favor, recarregue a página e tente novamente.');
        }
    }
}
