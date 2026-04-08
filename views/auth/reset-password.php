<?php $pageTitle = 'Redefinir Senha'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        .auth-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1a0533 0%, #2d1b69 50%, #1a0533 100%); padding: 20px; }
        .auth-card { background: #fff; border-radius: 16px; padding: 40px; max-width: 440px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .auth-logo { text-align: center; margin-bottom: 30px; }
        .auth-logo h1 { font-family: 'Georgia', serif; color: #6B21A8; font-size: 28px; margin: 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 14px; }
        .form-group input { width: 100%; padding: 12px 16px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; box-sizing: border-box; }
        .form-group input:focus { outline: none; border-color: #6B21A8; }
        .btn-primary { width: 100%; padding: 14px; background: linear-gradient(135deg, #6B21A8, #9333EA); color: #fff; border: none; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(107,33,168,0.4); }
        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
        .alert-error { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-logo">
                <h1>✦ Sunyan Nunes</h1>
                <p>Crie uma nova senha</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('reset-password') ?>">
                <?= CSRF::field() ?>
                <input type="hidden" name="token" value="<?= e($token) ?>">

                <div class="form-group">
                    <label for="password">Nova senha</label>
                    <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirmar nova senha</label>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Repita a senha" required>
                </div>

                <button type="submit" class="btn-primary">Redefinir senha</button>
            </form>
        </div>
    </div>
</body>
</html>
