<?php $pageTitle = 'Criar Conta'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        .auth-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1a0533 0%, #2d1b69 50%, #1a0533 100%); padding: 20px; }
        .auth-card { background: #fff; border-radius: 16px; padding: 40px; max-width: 480px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .auth-logo { text-align: center; margin-bottom: 30px; }
        .auth-logo h1 { font-family: 'Georgia', serif; color: #6B21A8; font-size: 28px; margin: 0; }
        .auth-logo p { color: #888; font-size: 14px; margin-top: 5px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 14px; }
        .form-group input { width: 100%; padding: 12px 16px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; box-sizing: border-box; }
        .form-group input:focus { outline: none; border-color: #6B21A8; }
        .form-group .hint { font-size: 12px; color: #888; margin-top: 4px; }
        .btn-primary { width: 100%; padding: 14px; background: linear-gradient(135deg, #6B21A8, #9333EA); color: #fff; border: none; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(107,33,168,0.4); }
        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
        .alert-error { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }
        .auth-links { text-align: center; margin-top: 20px; font-size: 14px; }
        .auth-links a { color: #6B21A8; text-decoration: none; font-weight: 600; }
        .auth-links a:hover { text-decoration: underline; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        @media (max-width: 480px) { .form-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-logo">
                <h1>✦ Sunyan Nunes</h1>
                <p>Crie sua conta e faça parte da comunidade</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('register') ?>">
                <?= CSRF::field() ?>

                <div class="form-group">
                    <label for="name">Nome completo</label>
                    <input type="text" id="name" name="name" value="<?= old('name') ?>" placeholder="Seu nome" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?= old('email') ?>" placeholder="seu@email.com" required>
                </div>

                <div class="form-group">
                    <label for="anonymous_name">Seu nome na comunidade</label>
                    <input type="text" id="anonymous_name" name="anonymous_name" value="<?= old('anonymousName') ?>" placeholder="Ex: Lua Dourada, Estrela Cósmica..." required minlength="3" maxlength="50">
                    <p class="hint">Este será seu pseudônimo na comunidade. Ninguém verá seu nome real.</p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Confirmar senha</label>
                        <input type="password" id="password_confirm" name="password_confirm" placeholder="Repita a senha" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Criar minha conta</button>
            </form>

            <div class="auth-links">
                <p>Já tem uma conta? <a href="<?= url('login') ?>">Entrar</a></p>
                <p style="margin-top:15px;"><a href="<?= url('') ?>" style="color:#888;">← Voltar ao site</a></p>
            </div>
        </div>
    </div>
</body>
</html>
