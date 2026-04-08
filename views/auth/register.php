<?php $pageTitle = 'Criar Conta'; ?>
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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card auth-card-wide">
            <div class="auth-card-toolbar">
                <?= themeToggleButton('theme-toggle theme-toggle-card', 'Modo claro') ?>
            </div>
            <div class="auth-logo">
                <h1>MULHER ESPIRAL</h1>
                <p>Crie sua conta para acessar os modulos, salvar seu progresso e entrar na comunidade com seguranca.</p>
                <div class="gold-line"></div>
            </div>

            <div class="auth-benefits">
                <span>Acesso imediato</span>
                <span class="trust-dot"></span>
                <span>Nome anonimo</span>
                <span class="trust-dot"></span>
                <span>Jornada organizada</span>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('register') ?>">
                <?= CSRF::field() ?>

                <div class="form-group">
                    <label for="name">Nome completo</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= old('name') ?>" placeholder="Seu nome" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="seu@email.com" required>
                </div>

                <div class="form-group">
                    <label for="anonymous_name">Seu nome na comunidade</label>
                    <input type="text" id="anonymous_name" name="anonymous_name" class="form-control" value="<?= old('anonymous_name') ?>" placeholder="Ex: Lua Dourada, Estrela Cosmica..." required minlength="3" maxlength="50">
                    <p class="text-xs text-muted mt-1" style="line-height:1.5;">Este sera seu pseudonimo na comunidade. Ninguem vera seu nome real.</p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Minimo 6 caracteres" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Confirmar senha</label>
                        <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Repita a senha" required>
                    </div>
                </div>

                <button type="submit" class="auth-submit">Criar conta e continuar</button>
            </form>

            <p class="auth-action-note">Depois do cadastro, voce entra direto na sua area de membros.</p>

            <div class="auth-links">
                <p>Ja tem uma conta? <a href="<?= url('login') ?>">Entrar</a></p>
                <p style="margin-top:18px;"><a href="<?= url('') ?>" class="back-link">&#8592; Voltar ao site</a></p>
            </div>
        </div>
    </div>
    <?= themeScriptTag() ?>
</body>
</html>
