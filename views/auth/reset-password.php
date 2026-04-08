<?php $pageTitle = 'Redefinir Senha'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    <meta name="theme-color" content="#0A0A0A" id="themeColorMeta">
    <?= themeInitScript() ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-card-toolbar">
                <?= themeToggleButton('theme-toggle theme-toggle-card', 'Modo claro') ?>
            </div>
            <div class="auth-logo">
                <h1>MULHER ESPIRAL</h1>
                <p>Defina sua nova senha para retomar sua jornada com seguranca.</p>
                <div class="gold-line"></div>
            </div>

            <div class="auth-benefits">
                <span>Acesso protegido</span>
                <span class="trust-dot"></span>
                <span>Troca rapida</span>
                <span class="trust-dot"></span>
                <span>Retorno imediato</span>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('reset-password') ?>">
                <?= CSRF::field() ?>
                <input type="hidden" name="token" value="<?= e($token) ?>">

                <div class="form-group">
                    <label for="password">Nova senha</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Minimo 6 caracteres" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirmar nova senha</label>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Repita a senha" required>
                </div>

                <button type="submit" class="auth-submit">Salvar nova senha</button>
            </form>

            <p class="auth-action-note">Depois disso, voce ja pode entrar normalmente na sua area.</p>
        </div>
    </div>
    <?= themeScriptTag() ?>
</body>
</html>
