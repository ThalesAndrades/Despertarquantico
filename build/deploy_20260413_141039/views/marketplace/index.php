<?php
$pageTitle = 'Marketplace';
$q = isset($q) ? (string) $q : '';
$qSafe = e($q);
$canonicalUrl = rtrim(APP_URL, '/') . '/marketplace';
$metaDescription = 'Marketplace oficial da ' . APP_NAME . ': cursos e jornadas digitais para transformacao feminina, com checkout seguro e acesso imediato.';
$ogTitle = $pageTitle . ' | ' . APP_NAME;
$brandImagePath = 'images/landing/home-brand.webp';
if (!is_file(BASE_PATH . '/public/' . $brandImagePath)) {
    $brandImagePath = 'images/landing/hero-essencia.svg';
}
$ogImageUrl = asset($brandImagePath);

$itemList = [
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => $pageTitle,
    'itemListElement' => []
];
$pos = 1;
foreach ($products as $p) {
    $itemList['itemListElement'][] = [
        '@type' => 'ListItem',
        'position' => $pos++,
        'url' => url('marketplace/' . $p['slug']),
        'name' => $p['title']
    ];
}
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
    <meta property="og:type" content="website">
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
    <script type="application/ld+json"><?= json_encode($itemList, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
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

<section class="marketplace-hero">
    <div class="container marketplace-hero-grid">
        <div class="marketplace-hero-copy">
            <span class="hero-badge">
                <span class="hero-badge-dot"></span>
                Curadoria oficial da Sunyan
            </span>
            <h1 class="marketplace-title">Escolha seu proximo passo com clareza — e começe hoje.</h1>
            <p class="marketplace-subtitle">Cursos e jornadas digitais para transformacao feminina, com estrutura, profundidade e um caminho objetivo do “eu sei o que eu sinto” ao “eu sei o que eu faço agora”.</p>
            <div class="marketplace-hero-actions">
                <a href="#catalogo" class="btn btn-gold btn-lg">Explorar produtos</a>
                <a href="<?= url('login') ?>" class="btn btn-outline btn-lg">Ja sou aluna</a>
            </div>
            <div class="marketplace-hero-meta">
                <span><?= count($products) ?> infoproduto<?= count($products) === 1 ? '' : 's' ?> disponivel<?= count($products) === 1 ? '' : 'eis' ?></span>
                <span class="trust-dot"></span>
                <span>Checkout seguro (Asaas)</span>
                <span class="trust-dot"></span>
                <span>Acesso imediato</span>
            </div>
            <div class="marketplace-trust-row" aria-label="Sinais de confianca">
                <span class="marketplace-trust-item">PIX • cartao • boleto</span>
                <span class="trust-dot"></span>
                <span class="marketplace-trust-item">Entrega digital</span>
                <span class="trust-dot"></span>
                <span class="marketplace-trust-item">Suporte via area de membros</span>
            </div>
        </div>
        <div class="marketplace-hero-visual">
            <div class="marketplace-hero-card">
                <img src="<?= asset('images/landing/hero-essencia.svg') ?>" alt="Curadoria visual dos infoprodutos Mulher Espiral" loading="eager" decoding="async">
            </div>
        </div>
    </div>
</section>

<section class="section marketplace-steps" aria-label="Como funciona">
    <div class="container">
        <div class="marketplace-steps-grid">
            <div class="marketplace-step">
                <div class="marketplace-step-kicker">1</div>
                <h3>Escolha com contexto</h3>
                <p>Veja claramente o que voce recebe, para quem e, e o proximo passo recomendado.</p>
            </div>
            <div class="marketplace-step">
                <div class="marketplace-step-kicker">2</div>
                <h3>Pague com seguranca</h3>
                <p>Checkout transparente e rapido, com meios de pagamento brasileiros (Asaas).</p>
            </div>
            <div class="marketplace-step">
                <div class="marketplace-step-kicker">3</div>
                <h3>Acesse imediatamente</h3>
                <p>Conteudo liberado apos a confirmacao, dentro do ecossistema Mulher Espiral.</p>
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
            <div class="marketplace-heading-side">
                <p class="marketplace-heading-copy">Encontre pelo nome (ou explore pelo instinto). O foco aqui e tirar friccao e colocar voce no caminho certo rapidamente.</p>
                <form class="marketplace-search" method="get" action="<?= url('marketplace') ?>" role="search">
                    <label class="sr-only" for="marketplace-search-q">Buscar produtos</label>
                    <input
                        id="marketplace-search-q"
                        name="q"
                        type="search"
                        value="<?= $qSafe ?>"
                        placeholder="Buscar por nome ou tema…"
                        autocomplete="off"
                        inputmode="search"
                    >
                    <button class="btn btn-outline marketplace-search-btn" type="submit">Buscar</button>
                </form>
                <?php if ($q !== ''): ?>
                    <div class="marketplace-search-meta">
                        <span><?= count($products) ?> resultado<?= count($products) === 1 ? '' : 's' ?> para “<?= $qSafe ?>”</span>
                        <a class="marketplace-search-clear" href="<?= url('marketplace') ?>">Limpar</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (empty($products)): ?>
            <div class="marketplace-empty">
                <?php if ($q !== ''): ?>
                    <h3>Nenhum produto encontrado.</h3>
                    <p>Tente buscar com outras palavras ou volte para ver o catalogo completo.</p>
                <?php else: ?>
                    <h3>Nenhum infoproduto publicado no momento.</h3>
                    <p>Quando a Sunyan adicionar novos produtos no painel, eles aparecerao automaticamente aqui.</p>
                <?php endif; ?>
                <div class="marketplace-empty-actions">
                    <a class="btn btn-gold" href="<?= url('marketplace') ?>">Ver catalogo completo</a>
                    <a class="btn btn-outline" href="<?= url('') ?>">Voltar ao inicio</a>
                </div>
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

<section class="section marketplace-faq" aria-label="Perguntas frequentes">
    <div class="container">
        <span class="section-label">FAQ</span>
        <h2 class="section-title text-left">Perguntas rapidas, respostas diretas.</h2>
        <div class="marketplace-faq-grid">
            <details class="marketplace-faq-item">
                <summary>Quando eu recebo o acesso?</summary>
                <p>O acesso e liberado apos a confirmacao do pagamento. PIX costuma liberar mais rapido. Cartao e boleto seguem o prazo do meio escolhido.</p>
            </details>
            <details class="marketplace-faq-item">
                <summary>Quais formas de pagamento eu posso usar?</summary>
                <p>PIX, cartao e boleto — com checkout seguro via Asaas.</p>
            </details>
            <details class="marketplace-faq-item">
                <summary>Posso comprar mesmo sem cadastro?</summary>
                <p>Sim. O checkout coleta seu email para envio das instrucoes. Depois, voce cria (ou acessa) sua conta para consumir o conteudo.</p>
            </details>
            <details class="marketplace-faq-item">
                <summary>Existe direito de arrependimento?</summary>
                <p>Sim. Compras online contam com direito de arrependimento em ate 7 dias (conforme Codigo de Defesa do Consumidor).</p>
            </details>
        </div>
    </div>
</section>

<?= themeScriptTag() ?>
<script src="<?= asset('js/landing.js') ?>" defer></script>
</body>
</html>
