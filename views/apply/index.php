<?php
$pageTitle = 'Aplicação — Mentoria Premium';
$metaDescription = 'Envie sua aplicação para a mentoria premium do Despertar Espiral. Um processo seletivo para mulheres prontas para uma transformação profunda.';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= e($pageTitle) ?> - <?= e(APP_NAME) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="theme-color" content="#0A0A0A">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/landing.css') ?>">
</head>
<body>
<nav class="landing-nav scrolled">
    <div class="container flex-between">
        <a href="<?= url('') ?>" class="nav-logo">DESPERTAR ESPIRAL</a>
        <div class="nav-links open">
            <a href="<?= url('marketplace') ?>">Cursos</a>
            <a href="<?= url('login') ?>" class="nav-cta-btn">Acessar</a>
        </div>
    </div>
</nav>

<section class="hero" style="min-height:auto;padding:120px 20px 70px;">
    <div class="hero-bg-effects">
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
    </div>
    <div class="container hero-content">
        <span class="hero-badge">
            <span class="hero-badge-dot"></span>
            Processo seletivo
        </span>
        <h1 class="hero-title">
            Mentoria Premium<br>
            para a sua <em>virada real</em>
        </h1>
        <p class="hero-subtitle" style="max-width:720px;">
            Este não é um formulário. É uma decisão. Se você sente que está pronta para parar de repetir padrões
            e assumir uma transformação profunda, envie sua aplicação. Eu leio pessoalmente.
        </p>
    </div>
</section>

<section class="section section-dark" style="padding:70px 0;">
    <div class="container container-sm">
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>

        <div class="prose-block" style="margin-bottom:26px;">
            <p class="prose-lead">Antes de enviar:</p>
            <p>Para honrar seu tempo (e o meu), responda com verdade. Não existe resposta “certa”. Existe prontidão.</p>
        </div>

        <form method="post" action="<?= url('aplicacao') ?>" class="card" style="padding:26px;border-radius:16px;">
            <?= CSRF::field() ?>

            <div class="form-group">
                <label for="name">Seu nome</label>
                <input class="form-control" id="name" name="name" type="text" value="<?= old('name') ?>" autocomplete="name" required>
            </div>

            <div class="form-group">
                <label for="email">Seu e-mail</label>
                <input class="form-control" id="email" name="email" type="email" value="<?= old('email') ?>" autocomplete="email" required>
            </div>

            <div class="form-group">
                <label for="whatsapp">WhatsApp</label>
                <input class="form-control" id="whatsapp" name="whatsapp" type="text" value="<?= old('whatsapp') ?>" inputmode="tel" autocomplete="tel" required>
            </div>

            <div class="form-group">
                <label for="moment">Em que momento você está hoje?</label>
                <textarea class="form-control" id="moment" name="moment" required><?= old('moment') ?></textarea>
            </div>

            <div class="form-group">
                <label for="goal">O que precisa mudar nos próximos 90 dias?</label>
                <textarea class="form-control" id="goal" name="goal" required><?= old('goal') ?></textarea>
            </div>

            <button type="submit" class="btn btn-gold btn-block btn-lg">Enviar aplicação</button>
            <p style="margin:14px 0 0;color:rgba(255,255,255,0.45);font-size:12px;text-align:center;">
                Ao enviar, você autoriza receber e-mails de orientação e próximos passos.
            </p>
        </form>
    </div>
</section>

<section class="section" style="padding:80px 0;">
    <div class="container container-sm">
        <div class="prose-block">
            <p class="prose-lead">Se você não for selecionada agora</p>
            <p>Você ainda vai receber uma rota de próximos passos — com profundidade, sem motivação vazia.</p>
        </div>
    </div>
</section>

<script src="<?= asset('js/landing.js') ?>"></script>
</body>
</html>

