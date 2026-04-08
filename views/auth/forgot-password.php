<?php $pageTitle = 'Esqueci minha senha'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
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
                <p>Recupere sua senha</p>
                <div class="gold-line"></div>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= e($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('forgot-password') ?>">
                <?= CSRF::field() ?>

                <div class="form-group">
                    <label for="email">Seu e-mail cadastrado</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="seu@email.com" required>
                </div>

                <button type="submit" class="auth-submit">Enviar link de recuperacao</button>
            </form>

            <div class="auth-links">
                <p><a href="<?= url('login') ?>">&#8592; Voltar ao login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
