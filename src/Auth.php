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

    public static function loginById(int $userId): bool
    {
        $user = Database::fetch("SELECT * FROM users WHERE id = ? AND is_active = 1", [$userId]);
        if (!$user) {
            return false;
        }
        self::setSession($user);
        return true;
    }

    public static function logout(): void
    {
        ensureSessionStarted();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            // PHP 7.4+: use options array so SameSite is respected during deletion.
            $secure = !empty($params['secure']);
            if (!$secure && !empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
                $secure = strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https';
            }
            $cookieOptions = [
                'expires' => time() - 42000,
                'path' => $params['path'] ?: '/',
                'secure' => $secure,
                'httponly' => !empty($params['httponly']),
                'samesite' => 'Lax',
            ];
            if (!empty($params['domain'])) {
                $cookieOptions['domain'] = $params['domain'];
            }
            setcookie(session_name(), '', $cookieOptions);
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

        return self::createResetTokenForUserId((int) $user['id']);
    }

    /**
     * Create a password reset / set-password token for an existing user id.
     * Useful for guest checkout flows where we create a user on payment confirmation.
     */
    public static function createResetTokenForUserId(int $userId): ?string
    {
        $user = Database::fetch(
            "SELECT id FROM users WHERE id = ? AND is_active = 1",
            [$userId]
        );
        if (!$user) {
            return null;
        }

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        Database::query(
            "UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?",
            [$token, $expires, (int) $user['id']]
        );

        return $token;
    }

    /**
     * Ensure a local user exists for a paid guest checkout (email-based).
     * Returns: ['id' => int, 'created' => bool]
     */
    public static function ensureUserForGuestPurchase(string $name, string $email): array
    {
        $email = self::normalizeEmail($email);
        $name = trim($name) !== '' ? trim($name) : 'Membro';

        $existing = Database::fetch("SELECT id FROM users WHERE LOWER(email) = ?", [$email]);
        if ($existing) {
            return ['id' => (int) $existing['id'], 'created' => false];
        }

        // Create a random password hash (user will set a real password via email link).
        $hash = password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT);
        $anonymousName = self::generateUniqueAnonymousName($name);

        $id = Database::insert(
            "INSERT INTO users (name, email, password_hash, anonymous_name, auth_provider) VALUES (?, ?, ?, ?, 'local')",
            [$name, $email, $hash, $anonymousName]
        );

        return ['id' => (int) $id, 'created' => true];
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

    private static function generateUniqueAnonymousName(string $name): string
    {
        $base = trim(preg_replace('/\s+/', ' ', $name));
        $base = preg_replace('/[^a-zA-ZÀ-ÿ0-9 ]/u', '', $base);
        $first = trim(explode(' ', $base)[0] ?? '');
        $first = $first !== '' ? $first : 'Espiral';
        $first = function_exists('mb_substr') ? mb_substr($first, 0, 18) : substr($first, 0, 18);

        for ($i = 0; $i < 12; $i++) {
            $suffix = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $candidate = $first . $suffix;
            $exists = Database::fetch("SELECT id FROM users WHERE anonymous_name = ?", [$candidate]);
            if (!$exists) {
                return $candidate;
            }
        }

        return 'Espiral' . bin2hex(random_bytes(3));
    }
}
