<?php
$pageTitle = $product['title'];
$checkoutUrl = url('checkout/' . $product['slug']);
$coverUrl = $product['cover_image'] ? url('uploads/' . $product['cover_image']) : asset('images/landing/story-jornada.svg');
$priceFormatted = 'R$ ' . number_format((float) $product['price'], 2, ',', '.');
$canonicalUrl = rtrim(APP_URL, '/') . '/marketplace/' . $product['slug'];
$metaDescription = $product['short_description'] ?: $product['title'];
$ogTitle = $product['title'] . ' | ' . APP_NAME;
$ogImageUrl = $coverUrl;

$jsonLdProduct = [
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product['title'],
    'description' => $metaDescription,
    'image' => [$ogImageUrl],
    'brand' => [
        '@type' => 'Brand',
        'name' => APP_NAME
    ],
    'offers' => [
        '@type' => 'Offer',
        'url' => $checkoutUrl,
        'priceCurrency' => 'BRL',
        'price' => (string) $product['price'],
        'availability' => 'https://schema.org/InStock',
        'itemCondition' => 'https://schema.org/NewCondition'
    ]
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= e($ogTitle) ?></title>
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="theme-color" content="#0A0A0A" id="themeColorMeta">
    <?= themeInitScript() ?>
    <meta property="og:title" content="<?= e($ogTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:type" content="product">
    <meta property="og:site_name" content="<?= e(APP_NAME) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:image" content="<?= e($ogImageUrl) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($ogTitle) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <meta name="twitter:image" content="<?= e($ogImageUrl) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/landing.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/marketplace.css') ?>">
    <script type="application/ld+json"><?= json_encode($jsonLdProduct, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</head>
<body class="landing-page marketplace-page">
<nav class="landing-nav" id="nav">
    <div class="container flex-between">
        <a href="<?= url('') ?>" class="nav-logo">DESPERTAR ESPIRAL <span class="nav-logo-sub">Mulher Espiral</span></a>
        <div class="nav-links" id="navLinks">
            <a href="<?= url('') ?>">Inicio</a>
            <a href="<?= url('marketplace') ?>">Marketplace</a>
            <?= themeToggleButton('theme-toggle theme-toggle-nav', 'Tema') ?>
            <a href="<?= url('login') ?>" class="nav-cta-btn">Acessar</a>
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
            <div class="marketplace-product-price" id="comprar"><?= e($priceFormatted) ?></div>
            <div class="marketplace-product-actions">
                <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-lg">Comprar agora</a>
                <a href="<?= url('marketplace') ?>" class="btn btn-outline btn-lg">Ver outros produtos</a>
            </div>
            <p class="marketplace-helper">Ao clicar, voce segue para um checkout seguro e recebe o acesso com clareza sobre o proximo passo.</p>
            <div class="marketplace-trust-row" aria-label="Sinais de confianca">
                <span class="marketplace-trust-item">PIX • cartao • boleto</span>
                <span class="trust-dot"></span>
                <span class="marketplace-trust-item">Checkout Asaas</span>
                <span class="trust-dot"></span>
                <span class="marketplace-trust-item">Direito de arrependimento 7 dias</span>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container marketplace-detail-grid">
        <div class="marketplace-detail-main">
            <span class="section-label">SOBRE ESTE PRODUTO</span>
            <h2 class="section-title text-left">O que voce recebe nesta experiencia.</h2>
            <div class="marketplace-highlight-grid" aria-label="Destaques">
                <div class="marketplace-highlight">
                    <h3>Estrutura clara</h3>
                    <p>Conteudo organizado em modulos e aulas para reduzir ruido mental e aumentar consistencia.</p>
                </div>
                <div class="marketplace-highlight">
                    <h3>Aplicacao pratica</h3>
                    <p>Foco em transformar insight em acao: pequenos passos, com direcao e intencao.</p>
                </div>
                <div class="marketplace-highlight">
                    <h3>Acesso no seu ritmo</h3>
                    <p>Voce avanca no tempo que faz sentido, com acesso digital e progresso dentro da plataforma.</p>
                </div>
            </div>
            <div class="marketplace-richtext">
                <?= $product['description'] ?: '<p>Este infoproduto foi estruturado para conduzir a usuaria por uma experiencia clara, profunda e aplicada, com conteudo organizado e acesso imediato.</p>' ?>
            </div>

            <div class="marketplace-audience" aria-label="Para quem e">
                <span class="section-label">DIRECIONAMENTO</span>
                <h2 class="section-title text-left">Para quem e (e para quem nao e).</h2>
                <div class="marketplace-audience-grid">
                    <div class="marketplace-audience-card">
                        <h3>Faz sentido se voce…</h3>
                        <ul>
                            <li>quer sair do “eu sei” e ir para o “eu sustento na pratica”.</li>
                            <li>precisa de estrutura gentil (sem perder profundidade).</li>
                            <li>busca um proximo passo claro, sem se perder em excesso de conteudo.</li>
                        </ul>
                    </div>
                    <div class="marketplace-audience-card marketplace-audience-card--muted">
                        <h3>Talvez nao seja agora se voce…</h3>
                        <ul>
                            <li>procura “solucao instantanea” sem processo.</li>
                            <li>quer apenas consumo passivo (sem aplicacao).</li>
                            <li>nao tem hoje espaco minimo para praticar 10–20 min por semana.</li>
                        </ul>
                    </div>
                </div>
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
            <div class="marketplace-sidebar-card marketplace-sidebar-cta">
                <h3>Pronta para comecar?</h3>
                <p class="marketplace-sidebar-copy">Um clique leva voce ao checkout seguro. Sem passos desnecessarios.</p>
                <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-lg marketplace-sidebar-cta-btn" aria-label="Comprar agora por <?= e($priceFormatted) ?>">Comprar agora</a>
                <p class="marketplace-helper">Voce ve o resumo do pagamento antes de confirmar.</p>
            </div>
        </aside>
    </div>
</section>

<section class="section marketplace-faq" aria-label="Perguntas frequentes">
    <div class="container">
        <span class="section-label">FAQ</span>
        <h2 class="section-title text-left">Tudo o que voce precisa saber antes de comprar.</h2>
        <div class="marketplace-faq-grid">
            <details class="marketplace-faq-item">
                <summary>Como eu acesso depois da compra?</summary>
                <p>Depois da confirmacao do pagamento, voce recebe as instrucoes no email e acessa pela area de membros. Se ja for aluna, o produto aparece no seu painel.</p>
            </details>
            <details class="marketplace-faq-item">
                <summary>Esse produto tem prazo para terminar?</summary>
                <p>Em geral, o acesso e digital e voce faz no seu ritmo. Se existir alguma janela especial, isso aparece na descricao do produto.</p>
            </details>
            <details class="marketplace-faq-item">
                <summary>Posso parcelar?</summary>
                <p>Quando disponivel, o parcelamento aparece no checkout (cartao). PIX e boleto seguem como pagamento a vista.</p>
            </details>
            <details class="marketplace-faq-item">
                <summary>E se eu desistir?</summary>
                <p>Compras online contam com direito de arrependimento em ate 7 dias (conforme Codigo de Defesa do Consumidor).</p>
            </details>
        </div>
    </div>
</section>

<div class="marketplace-sticky-cta" role="region" aria-label="Comprar este produto">
    <div class="marketplace-sticky-cta-inner">
        <div class="marketplace-sticky-cta-price">
            <span class="marketplace-sticky-cta-label">Investimento</span>
            <strong><?= e($priceFormatted) ?></strong>
        </div>
        <a class="btn btn-gold marketplace-sticky-cta-btn" href="<?= e($checkoutUrl) ?>" aria-label="Comprar agora por <?= e($priceFormatted) ?>">Comprar agora</a>
        <a class="btn btn-outline marketplace-sticky-cta-secondary" href="<?= url('marketplace') ?>" aria-label="Ver outros produtos">Outros</a>
    </div>
</div>

<?= themeScriptTag() ?>
<script src="<?= asset('js/landing.js') ?>" defer></script>
</body>
</html>
