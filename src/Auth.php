<?php
/**
 * Authentication Handler
 *
 * Hardened with:
 *  - case-insensitive email lookup (LOWER()) + normalized input
 *  - login rate limit (5 attempts per IP or email in 15 min)
 *  - session regeneration on login
 */
class Auth
{
    public const MAX_LOGIN_ATTEMPTS = 5;
    public const LOCKOUT_WINDOW_MIN = 15;

    public static function attempt(string $email, string $password): bool
    {
        $email = self::normalizeEmail($email);
        $ip = self::clientIp();

        if (self::isRateLimited($ip, $email)) {
            return false;
        }

        $user = Database::fetch(
            "SELECT * FROM users WHERE LOWER(email) = ? AND is_active = 1",
            [$email]
        );

        if (!$user || !password_verify($password, $user['password_hash'])) {
            self::recordFailedAttempt($ip, $email);
            return false;
        }

        self::clearAttempts($ip, $email);
        self::setSession($user);
        return true;
    }

    public static function register(string $name, string $email, string $password, string $anonymousName): ?string
    {
        $email = self::normalizeEmail($email);

        $existing = Database::fetch("SELECT id FROM users WHERE LOWER(email) = ?", [$email]);
        if ($existing) {
            return 'Este e-mail já está cadastrado.';
        }

        $existingAnon = Database::fetch("SELECT id FROM users WHERE anonymous_name = ?", [$anonymousName]);
        if ($existingAnon) {
            return 'Este nome na comunidade já está em uso. Escolha outro.';
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $id = Database::insert(
            "INSERT INTO users (name, email, password_hash, anonymous_name) VALUES (?, ?, ?, ?)",
            [$name, $email, $hash, $anonymousName]
        );

        $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$id]);
        self::setSession($user);
        return null;
    }

    public static function logout(): void
    {
        ensureSessionStarted();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
    }

    public static function createResetToken(string $email): ?string
    {
        $email = self::normalizeEmail($email);
        $user = Database::fetch(
            "SELECT id FROM users WHERE LOWER(email) = ? AND is_active = 1",
            [$email]
        );
        if (!$user) {
            return null;
        }

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        Database::query(
            "UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?",
            [$token, $expires, $user['id']]
        );

        return $token;
    }

    public static function resetPassword(string $token, string $newPassword): bool
    {
        $user = Database::fetch(
            "SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()",
            [$token]
        );

        if (!$user) {
            return false;
        }

        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        Database::query(
            "UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?",
            [$hash, $user['id']]
        );

        return true;
    }

    public static function isRateLimited(string $ip, string $email): bool
    {
        $window = self::LOCKOUT_WINDOW_MIN;
        $count = Database::count(
            "SELECT COUNT(*) FROM login_attempts
             WHERE (ip_address = ? OR email = ?)
               AND attempted_at > (NOW() - INTERVAL {$window} MINUTE)",
            [$ip, $email]
        );
        return $count >= self::MAX_LOGIN_ATTEMPTS;
    }

    private static function recordFailedAttempt(string $ip, string $email): void
    {
        try {
            Database::query(
                "INSERT INTO login_attempts (ip_address, email) VALUES (?, ?)",
                [$ip, $email]
            );
        } catch (Throwable $e) {
            error_log('login_attempts insert failed: ' . $e->getMessage());
        }
    }

    private static function clearAttempts(string $ip, string $email): void
    {
        try {
            Database::query(
                "DELETE FROM login_attempts WHERE ip_address = ? OR email = ?",
                [$ip, $email]
            );
        } catch (Throwable $e) {
            // Non-fatal — cleanup only.
        }
    }

    private static function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    private static function clientIp(): string
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        // X-Forwarded-For can be a comma-separated list; take the left-most (originating client).
        if (strpos($ip, ',') !== false) {
            $ip = trim(explode(',', $ip)[0]);
        }
        return substr($ip, 0, 45);
    }

    private static function setSession(array $user): void
    {
        ensureSessionStarted();
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_anonymous_name'] = $user['anonymous_name'];
        $_SESSION['user_role'] = $user['role'];
    }
}
