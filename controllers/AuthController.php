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
            $_SESSION['old_input'] = compact('name', 'email', 'anonymousName');
            redirect('register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'E-mail inválido.');
            $_SESSION['old_input'] = compact('name', 'email', 'anonymousName');
            redirect('register');
        }

        if (strlen($password) < 6) {
            flash('error', 'A senha deve ter pelo menos 6 caracteres.');
            $_SESSION['old_input'] = compact('name', 'email', 'anonymousName');
            redirect('register');
        }

        if ($password !== $passwordConfirm) {
            flash('error', 'As senhas não conferem.');
            $_SESSION['old_input'] = compact('name', 'email', 'anonymousName');
            redirect('register');
        }

        if (strlen($anonymousName) < 3 || strlen($anonymousName) > 50) {
            flash('error', 'O nome na comunidade deve ter entre 3 e 50 caracteres.');
            $_SESSION['old_input'] = compact('name', 'email', 'anonymousName');
            redirect('register');
        }

        $error = Auth::register($name, $email, $password, $anonymousName);
        if ($error) {
            flash('error', $error);
            $_SESSION['old_input'] = compact('name', 'email', 'anonymousName');
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
}
