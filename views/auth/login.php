<?php $pageTitle = 'Entrar'; ?>
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
    <style>
        .auth-page {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: #0A0A0A; padding: 20px;
            position: relative; overflow: hidden;
        }
        .auth-page::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 500px 400px at 30% 20%, rgba(201, 168, 76, 0.04) 0%, transparent 70%),
                radial-gradient(ellipse 400px 300px at 70% 80%, rgba(201, 168, 76, 0.03) 0%, transparent 70%);
            pointer-events: none;
        }
        .auth-card {
            background: #161616; border-radius: 20px; padding: 44px;
            max-width: 440px; width: 100%;
            border: 1px solid rgba(201, 168, 76, 0.12);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            position: relative;
        }
        .auth-logo { text-align: center; margin-bottom: 36px; }
        .auth-logo h1 {
            font-family: 'Playfair Display', serif; color: #C9A84C;
            font-size: 26px; margin: 0; letter-spacing: 1px;
        }
        .auth-logo p { color: rgba(255,255,255,0.35); font-size: 13px; margin-top: 8px; letter-spacing: 0.5px; }
        .auth-logo .gold-line { width: 40px; height: 1px; background: linear-gradient(90deg, transparent, #C9A84C, transparent); margin: 14px auto 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 500; color: rgba(255,255,255,0.55); margin-bottom: 6px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.8px; }
        .form-group input {
            width: 100%; padding: 13px 16px;
            border: 1.5px solid rgba(255,255,255,0.08); border-radius: 12px;
            font-size: 15px; transition: all 0.3s;
            box-sizing: border-box; background: #1A1A1A; color: #fff;
            font-family: 'Inter', sans-serif;
        }
        .form-group input::placeholder { color: rgba(255,255,255,0.25); }
        .form-group input:focus { outline: none; border-color: #C9A84C; box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.15); background: #1E1E1E; }
        .btn-submit {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, #C9A84C, #DFC06A); color: #0A0A0A;
            border: none; border-radius: 12px; font-size: 15px; font-weight: 700;
            cursor: pointer; transition: all 0.3s; font-family: 'Inter', sans-serif;
            letter-spacing: 0.3px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(201, 168, 76, 0.25); }
        .alert { padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; }
        .alert-error { background: rgba(255, 69, 58, 0.10); color: #FF6B63; border: 1px solid rgba(255, 69, 58, 0.20); }
        .alert-success { background: rgba(52, 199, 89, 0.10); color: #5CD67B; border: 1px solid rgba(52, 199, 89, 0.20); }
        .auth-links { text-align: center; margin-top: 24px; font-size: 13px; }
        .auth-links a { color: #C9A84C; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .auth-links a:hover { color: #DFC06A; }
        .auth-links p { margin-bottom: 8px; color: rgba(255,255,255,0.35); }
        .auth-links .back-link { color: rgba(255,255,255,0.25); font-size: 12px; }
        .auth-links .back-link:hover { color: rgba(255,255,255,0.5); }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-logo">
                <h1>MULHER ESPIRAL</h1>
                <p>Area exclusiva de membros</p>
                <div class="gold-line"></div>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= e($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('login') ?>">
                <?= CSRF::field() ?>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?= old('email') ?>" placeholder="seu@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Sua senha" required>
                </div>

                <button type="submit" class="btn-submit">Entrar</button>
            </form>

            <div class="auth-links">
                <p><a href="<?= url('forgot-password') ?>">Esqueceu sua senha?</a></p>
                <p>Ainda nao tem conta? <a href="<?= url('register') ?>">Criar conta</a></p>
                <p style="margin-top:18px;"><a href="<?= url('') ?>" class="back-link">&#8592; Voltar ao site</a></p>
            </div>
        </div>
    </div>
</body>
</html>
