<?php

class AuthController
{
    public function loginForm(): void
    {
        if (isLoggedIn()) {
            redirect('dashboard');
        }
        $error = flash('error');
        $success = flash('success');
        view('auth/login', compact('error', 'success'));
    }

    public function login(): void
    {
        CSRF::check();

        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            flash('error', 'Preencha todos os campos.');
            redirect('login');
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if (Auth::isRateLimited($ip, $email)) {
            flash('error', 'Muitas tentativas de login. Aguarde alguns minutos e tente novamente.');
            $_SESSION['old_input'] = ['email' => $email];
            redirect('login');
        }

        if (Auth::attempt($email, $password)) {
            $user = currentUser();
            EventDispatcher::dispatch('user.logged_in', [
                'email' => $email,
                'attributes' => [
                    'name' => $user['name'] ?? null,
                ],
                'properties' => [
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                    'user_id' => isset($user['id']) ? (int) $user['id'] : null,
                ],
            ]);
            redirect('dashboard');
        }

        flash('error', 'E-mail ou senha incorretos.');
        $_SESSION['old_input'] = ['email' => $email];
        redirect('login');
    }

    public function registerForm(): void
    {
        if (isLoggedIn()) {
            redirect('dashboard');
        }
        $error = flash('error');
        view('auth/register', compact('error'));
    }

    public function register(): void
    {
        CSRF::check();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $anonymousName = trim($_POST['anonymous_name'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($name) || empty($email) || empty($password) || empty($anonymousName)) {
            flash('error', 'Preencha todos os campos.');
            $_SESSION['old_input'] = ['name' => $name, 'email' => $email, 'anonymous_name' => $anonymousName];
            redirect('register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'E-mail inválido.');
            $_SESSION['old_input'] = ['name' => $name, 'email' => $email, 'anonymous_name' => $anonymousName];
            redirect('register');
        }

        if (strlen($password) < 6) {
            flash('error', 'A senha deve ter pelo menos 6 caracteres.');
            $_SESSION['old_input'] = ['name' => $name, 'email' => $email, 'anonymous_name' => $anonymousName];
            redirect('register');
        }

        if ($password !== $passwordConfirm) {
            flash('error', 'As senhas não conferem.');
            $_SESSION['old_input'] = ['name' => $name, 'email' => $email, 'anonymous_name' => $anonymousName];
            redirect('register');
        }

        if (strlen($anonymousName) < 3 || strlen($anonymousName) > 50) {
            flash('error', 'O nome na comunidade deve ter entre 3 e 50 caracteres.');
            $_SESSION['old_input'] = ['name' => $name, 'email' => $email, 'anonymous_name' => $anonymousName];
            redirect('register');
        }

        $error = Auth::register($name, $email, $password, $anonymousName);
        if ($error) {
            flash('error', $error);
            $_SESSION['old_input'] = ['name' => $name, 'email' => $email, 'anonymous_name' => $anonymousName];
            redirect('register');
        }

        unset($_SESSION['old_input']);

        EventDispatcher::dispatch('user.registered', [
            'email' => $email,
            'attributes' => [
                'name' => $name,
                'anonymous_name' => $anonymousName,
            ],
            'properties' => [
                'source' => 'landing',
            ],
        ]);

        flash('success', 'Conta criada com sucesso! Bem-vinda!');
        redirect('dashboard');
    }

    public function logout(): void
    {
        CSRF::check();
        Auth::logout();
        redirect('login');
    }

    public function forgotPasswordForm(): void
    {
        $error = flash('error');
        $success = flash('success');
        view('auth/forgot-password', compact('error', 'success'));
    }

    public function forgotPassword(): void
    {
        CSRF::check();

        $email = trim($_POST['email'] ?? '');
        if (empty($email)) {
            flash('error', 'Informe seu e-mail.');
            redirect('forgot-password');
        }

        $token = Auth::createResetToken($email);
        if ($token) {
            $resetUrl = APP_URL . '/reset-password?token=' . $token;
            EventDispatcher::dispatch('user.password_reset_requested', [
                'email' => $email,
                'properties' => [
                    'reset_url' => $resetUrl,
                    'reset_token_prefix' => substr($token, 0, 8),
                ],
            ]);
        }

        // Always show success to avoid email enumeration
        flash('success', 'Se o e-mail estiver cadastrado, você receberá um link para redefinir sua senha.');
        redirect('forgot-password');
    }

    public function resetPasswordForm(): void
    {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            redirect('login');
        }
        $error = flash('error');
        view('auth/reset-password', compact('token', 'error'));
    }

    public function resetPassword(): void
    {
        CSRF::check();

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (strlen($password) < 6) {
            flash('error', 'A senha deve ter pelo menos 6 caracteres.');
            redirect('reset-password?token=' . $token);
        }

        if ($password !== $passwordConfirm) {
            flash('error', 'As senhas não conferem.');
            redirect('reset-password?token=' . $token);
        }

        $emailToNotify = Database::fetch(
            "SELECT email FROM users WHERE reset_token = ? AND reset_expires > NOW()",
            [$token]
        );

        if (Auth::resetPassword($token, $password)) {
            if ($emailToNotify && !empty($emailToNotify['email'])) {
                EventDispatcher::dispatch('user.password_reset_completed', [
                    'email' => $emailToNotify['email'],
                    'properties' => [
                        'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                    ],
                ]);
            }
            flash('success', 'Senha redefinida com sucesso! Faça login.');
            redirect('login');
        }

        flash('error', 'Token inválido ou expirado. Solicite um novo link.');
        redirect('forgot-password');
    }

    public function googleStart(): void
    {
        if (isLoggedIn()) {
            redirect('dashboard');
        }

        ensureSessionStarted();
        require_once BASE_PATH . '/src/GoogleOAuth.php';
        $config = require BASE_PATH . '/config/google.php';

        if (empty($config['client_id']) || empty($config['client_secret'])) {
            flash('error', 'Login com Google indisponível no momento.');
            redirect('login');
        }

        $state = bin2hex(random_bytes(16));
        $nonce = bin2hex(random_bytes(16));
        $verifier = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

        $_SESSION['oauth_google'] = [
            'state' => $state,
            'nonce' => $nonce,
            'verifier' => $verifier,
            'created_at' => time(),
        ];

        $url = GoogleOAuth::buildAuthUrl($config, $state, $nonce, $verifier);
        header('Location: ' . $url);
        exit;
    }

    public function googleCallback(): void
    {
        ensureSessionStarted();
        require_once BASE_PATH . '/src/GoogleOAuth.php';
        $config = require BASE_PATH . '/config/google.php';

        $error = trim((string) ($_GET['error'] ?? ''));
        if ($error !== '') {
            flash('error', 'Login com Google cancelado.');
            redirect('login');
        }

        $code = (string) ($_GET['code'] ?? '');
        $state = (string) ($_GET['state'] ?? '');
        $session = is_array($_SESSION['oauth_google'] ?? null) ? $_SESSION['oauth_google'] : [];
        unset($_SESSION['oauth_google']);

        $createdAt = isset($session['created_at']) ? (int) $session['created_at'] : 0;
        if ($createdAt === 0 || (time() - $createdAt) > 900) {
            flash('error', 'Sessão expirada. Tente novamente.');
            redirect('login');
        }

        if ($code === '' || $state === '' || empty($session['state']) || !hash_equals((string) $session['state'], $state)) {
            flash('error', 'Não foi possível validar o login com Google.');
            redirect('login');
        }

        $tokens = GoogleOAuth::exchangeCode($config, $code, (string) ($session['verifier'] ?? ''));
        if (!$tokens || empty($tokens['id_token'])) {
            flash('error', 'Falha ao autenticar com Google.');
            redirect('login');
        }

        $claims = GoogleOAuth::validateIdToken($config, (string) $tokens['id_token'], (string) ($session['nonce'] ?? ''));
        if (!$claims) {
            flash('error', 'Não foi possível validar o login com Google.');
            redirect('login');
        }

        $email = strtolower(trim((string) $claims['email']));
        $googleId = (string) $claims['sub'];
        $name = trim((string) ($claims['name'] ?: ''));
        if ($name === '') {
            $name = trim((string) (($claims['given_name'] ?? '') . ' ' . ($claims['family_name'] ?? '')));
        }
        if ($name === '') {
            $name = 'Membro';
        }

        $emailVerified = !empty($claims['email_verified']);
        $avatarUrl = trim((string) ($claims['picture'] ?? ''));

        $user = Database::fetch("SELECT * FROM users WHERE google_id = ? AND is_active = 1", [$googleId]);
        if (!$user) {
            $byEmail = Database::fetch("SELECT * FROM users WHERE LOWER(email) = ? AND is_active = 1", [$email]);
            if ($byEmail && $emailVerified) {
                Database::query(
                    "UPDATE users SET google_id = ?, auth_provider = 'google', google_email_verified = 1, avatar_url = ? WHERE id = ?",
                    [$googleId, $avatarUrl !== '' ? $avatarUrl : null, (int) $byEmail['id']]
                );
                $user = Database::fetch("SELECT * FROM users WHERE id = ?", [(int) $byEmail['id']]);
            } elseif (!$byEmail) {
                $anon = $this->generateUniqueAnonymousName($name);
                $hash = password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT);
                $id = Database::insert(
                    "INSERT INTO users (name, email, password_hash, auth_provider, google_id, google_email_verified, avatar_url, anonymous_name) VALUES (?, ?, ?, 'google', ?, ?, ?, ?)",
                    [$name, $email, $hash, $googleId, $emailVerified ? 1 : 0, $avatarUrl !== '' ? $avatarUrl : null, $anon]
                );
                $user = Database::fetch("SELECT * FROM users WHERE id = ?", [(int) $id]);
            }
        }

        if (!$user) {
            flash('error', 'Não foi possível concluir o login com Google.');
            redirect('login');
        }

        Auth::loginById((int) $user['id']);

        EventDispatcher::dispatch('user.logged_in', [
            'email' => $email,
            'attributes' => [
                'name' => $user['name'] ?? null,
            ],
            'properties' => [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                'user_id' => isset($user['id']) ? (int) $user['id'] : null,
                'source' => 'google',
            ],
        ]);

        redirect('dashboard');
    }

    private function generateUniqueAnonymousName(string $name): string
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
