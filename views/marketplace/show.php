<?php
$pageTitle = $product['title'];
$checkoutUrl = url('checkout/' . $product['slug']);
$coverUrl = $product['cover_image'] ? url('uploads/' . $product['cover_image']) : asset('images/landing/story-jornada.svg');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    <meta name="description" content="<?= e($product['short_description'] ?: $product['title']) ?>">
    <meta name="theme-color" content="#0A0A0A" id="themeColorMeta">
    <?= themeInitScript() ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/landing.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/marketplace.css') ?>">
</head>
<body class="landing-page marketplace-page">
<nav class="landing-nav" id="nav">
    <div class="container flex-between">
        <a href="<?= url('') ?>" class="nav-logo">MULHER ESPIRAL</a>
        <div class="nav-links" id="navLinks">
            <a href="<?= url('') ?>">Inicio</a>
            <a href="<?= url('marketplace') ?>">Marketplace</a>
            <?= themeToggleButton('theme-toggle theme-toggle-nav', 'Modo claro') ?>
            <a href="<?= url('login') ?>" class="nav-cta-btn">Ja sou aluna</a>
        </div>
        <button class="nav-toggle" id="navToggle" aria-label="Abrir menu" aria-controls="navLinks" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<section class="marketplace-product-hero">
    <div class="container marketplace-product-grid">
        <div class="marketplace-product-media">
            <img src="<?= e($coverUrl) ?>" alt="<?= e($product['title']) ?>" fetchpriority="high" decoding="async">
        </div>
        <div class="marketplace-product-copy">
            <span class="hero-badge">
                <span class="hero-badge-dot"></span>
                Infoproduto oficial da Sunyan
            </span>
            <h1 class="marketplace-title"><?= e($product['title']) ?></h1>
            <p class="marketplace-subtitle"><?= e($product['short_description'] ?: 'Uma experiencia digital criada para aprofundar sua jornada com metodo, clareza e leveza.') ?></p>
            <div class="marketplace-product-meta">
                <span><?= (int) $product['module_count'] ?> modulo<?= (int) $product['module_count'] === 1 ? '' : 's' ?></span>
                <span class="trust-dot"></span>
                <span><?= (int) $product['lesson_count'] ?> aula<?= (int) $product['lesson_count'] === 1 ? '' : 's' ?></span>
                <span class="trust-dot"></span>
                <span>Acesso imediato</span>
            </div>
            <div class="marketplace-product-price">R$ <?= number_format((float) $product['price'], 2, ',', '.') ?></div>
            <div class="marketplace-product-actions">
                <a href="<?= $checkoutUrl ?>" class="btn btn-gold btn-lg">Comprar agora</a>
                <a href="<?= url('marketplace') ?>" class="btn btn-outline btn-lg">Ver outros produtos</a>
            </div>
            <p class="marketplace-helper">Ao clicar, voce segue para um checkout seguro e recebe o acesso com clareza sobre o proximo passo.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container marketplace-detail-grid">
        <div class="marketplace-detail-main">
            <span class="section-label">SOBRE ESTE PRODUTO</span>
            <h2 class="section-title text-left">O que voce recebe nesta experiencia.</h2>
            <div class="marketplace-richtext">
                <?= $product['description'] ?: '<p>Este infoproduto foi estruturado para conduzir a usuaria por uma experiencia clara, profunda e aplicada, com conteudo organizado e acesso imediato.</p>' ?>
            </div>
        </div>
        <aside class="marketplace-sidebar">
            <div class="marketplace-sidebar-card">
                <h3>Resumo rapido</h3>
                <ul class="marketplace-summary-list">
                    <li>Checkout seguro e objetivo</li>
                    <li>Acesso digital liberado apos confirmacao</li>
                    <li>Experiencia integrada ao ecossistema Mulher Espiral</li>
                </ul>
            </div>
            <?php if (!empty($modules)): ?>
                <div class="marketplace-sidebar-card">
                    <h3>Estrutura do conteudo</h3>
                    <div class="marketplace-module-list">
                        <?php foreach ($modules as $module): ?>
                            <div class="marketplace-module-item">
                                <strong><?= e($module['title']) ?></strong>
                                <span><?= (int) $module['lesson_count'] ?> aula<?= (int) $module['lesson_count'] === 1 ? '' : 's' ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</section>

<?= themeScriptTag() ?>
<script src="<?= asset('js/landing.js') ?>" defer></script>
</body>
</html>
