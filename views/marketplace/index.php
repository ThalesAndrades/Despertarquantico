<?php $pageTitle = 'Marketplace Mulher Espiral'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    <meta name="description" content="Explore os infoprodutos da Sunyan Nunes: cursos, jornadas e experiencias digitais com clareza, profundidade e acesso imediato.">
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

<section class="marketplace-hero">
    <div class="container marketplace-hero-grid">
        <div class="marketplace-hero-copy">
            <span class="hero-badge">
                <span class="hero-badge-dot"></span>
                Curadoria oficial da Sunyan
            </span>
            <h1 class="marketplace-title">Um marketplace elegante para reunir os infoprodutos da Sunyan.</h1>
            <p class="marketplace-subtitle">Aqui a Sunyan publica cursos, jornadas e experiencias digitais em um espaco claro, premium e facil de navegar. Cada produto tem proposito, proposta e proximo passo bem definidos.</p>
            <div class="marketplace-hero-actions">
                <a href="#catalogo" class="btn btn-gold btn-lg">Explorar produtos</a>
                <a href="<?= url('login') ?>" class="btn btn-outline btn-lg">Entrar na area de membros</a>
            </div>
            <div class="marketplace-hero-meta">
                <span><?= count($products) ?> infoproduto<?= count($products) === 1 ? '' : 's' ?> disponivel<?= count($products) === 1 ? '' : 'eis' ?></span>
                <span class="trust-dot"></span>
                <span>Checkout seguro</span>
                <span class="trust-dot"></span>
                <span>Acesso imediato</span>
            </div>
        </div>
        <div class="marketplace-hero-visual">
            <div class="marketplace-hero-card">
                <img src="<?= asset('images/landing/hero-essencia.svg') ?>" alt="Curadoria visual dos infoprodutos Mulher Espiral" loading="eager" decoding="async">
            </div>
        </div>
    </div>
</section>

<section class="section" id="catalogo">
    <div class="container">
        <div class="marketplace-heading">
            <div>
                <span class="section-label">CATALOGO</span>
                <h2 class="section-title text-left">Escolha o proximo passo da sua jornada.</h2>
            </div>
            <p class="marketplace-heading-copy">Cada infoproduto abaixo foi organizado para deixar claro o que ele entrega, para quem ele faz sentido e qual acao voce pode tomar agora.</p>
        </div>

        <?php if (empty($products)): ?>
            <div class="marketplace-empty">
                <h3>Nenhum infoproduto publicado no momento.</h3>
                <p>Quando a Sunyan adicionar novos produtos no painel, eles aparecerao automaticamente aqui.</p>
            </div>
        <?php else: ?>
            <div class="marketplace-grid">
                <?php foreach ($products as $product): ?>
                    <?php $coverUrl = $product['cover_image'] ? url('uploads/' . $product['cover_image']) : asset('images/landing/story-jornada.svg'); ?>
                    <article class="marketplace-card">
                        <a href="<?= url('marketplace/' . e($product['slug'])) ?>" class="marketplace-card-media">
                            <img src="<?= e($coverUrl) ?>" alt="<?= e($product['title']) ?>" loading="lazy" decoding="async">
                        </a>
                        <div class="marketplace-card-body">
                            <div class="marketplace-card-top">
                                <span class="marketplace-chip">Infoproduto digital</span>
                                <span class="marketplace-price">R$ <?= number_format((float) $product['price'], 2, ',', '.') ?></span>
                            </div>
                            <h3><?= e($product['title']) ?></h3>
                            <p><?= e($product['short_description'] ?: 'Uma experiencia digital da Sunyan criada para aprofundar sua jornada com clareza e leveza.') ?></p>
                            <div class="marketplace-card-stats">
                                <span><?= (int) $product['module_count'] ?> modulo<?= (int) $product['module_count'] === 1 ? '' : 's' ?></span>
                                <span class="trust-dot"></span>
                                <span><?= (int) $product['lesson_count'] ?> aula<?= (int) $product['lesson_count'] === 1 ? '' : 's' ?></span>
                            </div>
                            <div class="marketplace-card-actions">
                                <a href="<?= url('marketplace/' . e($product['slug'])) ?>" class="btn btn-outline">Ver detalhes</a>
                                <a href="<?= url('checkout/' . e($product['slug'])) ?>" class="btn btn-gold">Comprar agora</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= themeScriptTag() ?>
<script src="<?= asset('js/landing.js') ?>" defer></script>
</body>
</html>
