<?php $pageTitle = 'Criar Conta'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0A0A0A" id="themeColorMeta">
    <?= themeInitScript() ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card auth-card-wide">
            <div class="auth-logo">
                <h1>MULHER ESPIRAL</h1>
                <p>Crie sua conta e faca parte da comunidade</p>
                <div class="gold-line"></div>
            </div>
            <div style="display:flex;justify-content:center;margin:-6px 0 18px;">
                <?= themeToggleButton('theme-toggle', 'Tema') ?>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error" role="alert" aria-live="polite"><?= e($error) ?></div>
            <?php endif; ?>

            <a href="<?= url('auth/google') ?>" class="auth-submit auth-submit-google">Continuar com Google</a>
            <div class="auth-divider"><span>ou</span></div>

            <form method="POST" action="<?= url('register') ?>">
                <?= CSRF::field() ?>

                <div class="form-group">
                    <label for="name">Nome completo</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= old('name') ?>" placeholder="Seu nome" autocomplete="name" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="seu@email.com" autocomplete="email" inputmode="email" autocapitalize="none" required>
                </div>

                <div class="form-group">
                    <label for="anonymous_name">Seu nome na comunidade</label>
                    <input type="text" id="anonymous_name" name="anonymous_name" class="form-control" value="<?= old('anonymous_name') ?>" placeholder="Ex: Lua Dourada, Estrela Cosmica..." autocomplete="nickname" required minlength="3" maxlength="50" aria-describedby="anonHelp">
                    <p id="anonHelp" class="text-xs text-muted mt-1">Este sera seu pseudonimo na comunidade. Ninguem vera seu nome real.</p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Minimo 6 caracteres" autocomplete="new-password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Confirmar senha</label>
                        <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Repita a senha" autocomplete="new-password" required>
                    </div>
                </div>

                <button type="submit" class="auth-submit">Criar minha conta</button>
                <p class="auth-trust">Dados protegidos • Pseudonimo na comunidade • Sem spam</p>
            </form>

            <div class="auth-links">
                <p>Ja tem uma conta? <a href="<?= url('login') ?>">Entrar</a></p>
                <p class="mt-2"><a href="<?= url('') ?>" class="back-link">&#8592; Voltar ao site</a></p>
            </div>
        </div>
    </div>
    <?= themeScriptTag() ?>
</body>
</html>
