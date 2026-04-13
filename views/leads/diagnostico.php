<?php
$pageTitle = 'Diagnóstico';
$metaDescription = 'Um diagnóstico rápido para mapear sua dor, seu estágio e seu próximo passo real. Profundo, direto e aplicável.';
$utm = [
    'utm_source' => $_GET['utm_source'] ?? '',
    'utm_medium' => $_GET['utm_medium'] ?? '',
    'utm_campaign' => $_GET['utm_campaign'] ?? '',
    'utm_content' => $_GET['utm_content'] ?? '',
    'utm_term' => $_GET['utm_term'] ?? '',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= e($pageTitle) ?> | <?= e(APP_NAME) ?></title>
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
            <a href="<?= url('aplicacao') ?>" class="nav-cta-btn">Mentoria</a>
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
            3 minutos • clareza real
        </span>
        <h1 class="hero-title">
            Diagnóstico do seu<br>
            próximo passo
        </h1>
        <p class="hero-subtitle" style="max-width:720px;">
            Sem motivação vazia. Sem “dicas”. Um mapa simples para você voltar ao eixo e avançar.
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

        <form method="post" action="<?= url('diagnostico') ?>" class="card" style="padding:26px;border-radius:16px;">
            <?= CSRF::field() ?>
            <?php foreach ($utm as $k => $v): ?>
                <input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>">
            <?php endforeach; ?>

            <div class="form-group">
                <label for="name">Seu nome (opcional)</label>
                <input class="form-control" id="name" name="name" type="text" value="<?= old('name') ?>" autocomplete="name">
            </div>

            <div class="form-group">
                <label for="email">Seu e-mail</label>
                <input class="form-control" id="email" name="email" type="email" value="<?= old('email') ?>" autocomplete="email" required>
            </div>

            <div class="form-group">
                <label for="whatsapp">WhatsApp (opcional)</label>
                <input class="form-control" id="whatsapp" name="whatsapp" type="text" value="<?= old('whatsapp') ?>" inputmode="tel" autocomplete="tel">
            </div>

            <div class="form-group">
                <label>Qual dor está mais alta hoje?</label>
                <select class="form-control" name="pain_primary" required>
                    <option value="">Selecionar</option>
                    <option value="Ansiedade">Ansiedade / sobrecarga</option>
                    <option value="Relacionamentos">Relacionamentos / limites</option>
                    <option value="Dinheiro">Dinheiro / merecimento</option>
                    <option value="Propósito">Propósito / direção</option>
                </select>
            </div>

            <div class="form-group">
                <label>Seu estágio agora</label>
                <select class="form-control" name="stage" required>
                    <option value="">Selecionar</option>
                    <option value="Despertar">Despertar</option>
                    <option value="Reconstrução">Reconstrução</option>
                    <option value="Expansão">Expansão</option>
                </select>
            </div>

            <div class="form-group">
                <label>Seu perfil social (opcional)</label>
                <select class="form-control" name="social_archetype">
                    <option value="">Selecionar</option>
                    <option value="A Cuidadora">A cuidadora</option>
                    <option value="A Invisível">A invisível</option>
                    <option value="A Líder">A líder</option>
                    <option value="A Buscadora">A buscadora</option>
                    <option value="A Criadora">A criadora</option>
                </select>
            </div>

            <div class="form-group">
                <label for="moment">Em 1 frase: o que mais te trava hoje?</label>
                <textarea class="form-control" id="moment" name="moment" required><?= old('moment') ?></textarea>
            </div>

            <button type="submit" class="btn btn-gold btn-block btn-lg">Receber meu mapa por e-mail</button>
            <p style="margin:14px 0 0;color:rgba(255,255,255,0.45);font-size:12px;text-align:center;">
                Ao enviar, você autoriza receber e-mails de orientação e próximos passos.
            </p>
        </form>
    </div>
</section>

<script src="<?= asset('js/landing.js') ?>"></script>
</body>
</html>

