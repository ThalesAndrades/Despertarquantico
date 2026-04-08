<?php
/**
 * Authentication Handler
 */
class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $user = Database::fetch(
            "SELECT * FROM users WHERE email = ? AND is_active = 1",
            [$email]
        );

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        self::setSession($user);
        return true;
    }

    public static function register(string $name, string $email, string $password, string $anonymousName): ?string
    {
        $existing = Database::fetch("SELECT id FROM users WHERE email = ?", [$email]);
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
        $user = Database::fetch("SELECT id FROM users WHERE email = ? AND is_active = 1", [$email]);
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

    private static function setSession(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_anonymous_name'] = $user['anonymous_name'];
        $_SESSION['user_role'] = $user['role'];
    }
}
