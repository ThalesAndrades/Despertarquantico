<?php $pageTitle = 'Entrar'; ?>
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
        <div class="auth-card">
            <div class="auth-logo">
                <h1>MULHER ESPIRAL</h1>
                <p>Area exclusiva de membros</p>
                <div class="gold-line"></div>
            </div>
            <div style="display:flex;justify-content:center;margin:-6px 0 18px;">
                <?= themeToggleButton('theme-toggle', 'Tema') ?>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error" role="alert" aria-live="polite"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success" role="alert" aria-live="polite"><?= e($success) ?></div>
            <?php endif; ?>

            <a href="<?= url('auth/google') ?>" class="auth-submit auth-submit-google">Entrar com Google</a>
            <div class="auth-divider"><span>ou</span></div>

            <form method="POST" action="<?= url('login') ?>">
                <?= CSRF::field() ?>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="seu@email.com" autocomplete="email" inputmode="email" autocapitalize="none" required>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Sua senha" autocomplete="current-password" required>
                </div>

                <button type="submit" class="auth-submit">Entrar</button>
                <p class="auth-trust">Dados protegidos • Comunidade anonima • Sem spam</p>
            </form>

            <div class="auth-links">
                <p><a href="<?= url('forgot-password') ?>">Esqueceu sua senha?</a></p>
                <p>Ainda nao tem conta? <a href="<?= url('register') ?>">Criar conta</a></p>
                <p class="mt-2"><a href="<?= url('') ?>" class="back-link">&#8592; Voltar ao site</a></p>
            </div>
        </div>
    </div>
    <?= themeScriptTag() ?>
</body>
</html>
