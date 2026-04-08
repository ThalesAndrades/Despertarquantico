<?php $user = currentUser(); ?>

<?php
$featuredProduct = $products[0] ?? null;
$featuredProgress = $featuredProduct['progress'] ?? 0;
$featuredAction = $featuredProgress <= 0 ? 'Comecar minha jornada' : ($featuredProgress >= 100 ? 'Revisar conteudo' : 'Continuar de onde parei');
$featuredSupport = $featuredProgress <= 0
    ? 'Seu proximo passo esta pronto: entrar no primeiro modulo e avancar com seguranca.'
    : ($featuredProgress >= 100
        ? 'Voce ja concluiu este conteudo. Agora pode revisar os pontos-chave e aprofundar sua integracao.'
        : 'Seu progresso esta salvo. Continue exatamente do ponto em que voce parou.');
?>

<?php if ($featuredProduct): ?>
    <section class="dashboard-next-step">
        <div class="dashboard-next-step-label">
            <span>&#10022;</span>
            <span>Proxima melhor acao</span>
        </div>
        <h2><?= e($featuredAction) ?></h2>
        <p><?= e($featuredSupport) ?></p>
        <div class="dashboard-next-step-actions">
            <a href="<?= url('products/' . e($featuredProduct['slug'])) ?>" class="btn btn-gold"><?= e($featuredAction) ?></a>
            <a href="<?= url('community') ?>" class="btn btn-outline">Entrar na comunidade</a>
        </div>
        <div class="dashboard-next-step-meta">
            <span><?= $featuredProduct['progress'] ?>% concluido</span>
            <span><?= $featuredProduct['completed_lessons'] ?>/<?= $featuredProduct['total_lessons'] ?> aulas liberadas no seu ritmo</span>
        </div>
    </section>
<?php endif; ?>

<!-- Quick Links -->
<div class="quick-links">
    <a href="<?= url('products') ?>" class="quick-link">
        <div class="quick-link-icon purple">&#10022;</div>
        <span class="quick-link-text">Meus Produtos</span>
    </a>
    <a href="<?= url('community') ?>" class="quick-link">
        <div class="quick-link-icon gold">&#10023;</div>
        <span class="quick-link-text">Comunidade</span>
    </a>
    <a href="<?= url('') ?>" class="quick-link">
        <div class="quick-link-icon green">&#10025;</div>
        <span class="quick-link-text">Ver Novos Produtos</span>
    </a>
</div>

<!-- My Products -->
<div class="section-header">
    <h2>Meus Produtos</h2>
    <?php if (count($products) > 0): ?>
        <a href="<?= url('products') ?>" class="btn btn-sm btn-outline">Ver todos</a>
    <?php endif; ?>
</div>

<?php if (empty($products)): ?>
    <div class="empty-state">
        <div class="empty-icon">&#10022;</div>
        <h3 class="empty-title">Nenhum produto ainda</h3>
        <p class="empty-text">Voce ainda nao adquiriu nenhum produto. Explore nosso catalogo!</p>
        <a href="<?= url('') ?>" class="btn btn-primary">Explorar Produtos</a>
    </div>
<?php else: ?>
    <div class="products-grid">
        <?php foreach ($products as $prod): ?>
            <a href="<?= url('products/' . e($prod['slug'])) ?>" class="product-card product-card-link">
                <div class="product-card-img">
                    <?php if ($prod['cover_image']): ?>
                        <img src="<?= url('uploads/' . e($prod['cover_image'])) ?>" alt="<?= e($prod['title']) ?>" loading="lazy" decoding="async">
                    <?php else: ?>
                        <?= e($prod['title']) ?>
                    <?php endif; ?>
                </div>
                <div class="product-card-body">
                    <h3 class="product-card-title"><?= e($prod['title']) ?></h3>
                    <p class="product-card-desc"><?= e($prod['short_description'] ?? '') ?></p>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= $prod['progress'] ?>%"></div>
                    </div>
                    <div class="product-card-footer">
                        <span class="progress-text"><?= $prod['progress'] ?>% concluido</span>
                        <span class="progress-text"><?= $prod['completed_lessons'] ?>/<?= $prod['total_lessons'] ?> aulas</span>
                    </div>
                    <div class="mt-2">
                        <span class="btn btn-sm btn-outline">
                            <?= $prod['progress'] <= 0 ? 'Comecar' : ($prod['progress'] >= 100 ? 'Revisar' : 'Continuar') ?>
                        </span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Recent Community -->
<?php if (!empty($recentPosts)): ?>
    <div class="section-header mt-4">
        <h2>Comunidade</h2>
        <a href="<?= url('community') ?>" class="btn btn-sm btn-outline">Ver tudo</a>
    </div>
    <div class="grid dashboard-community-list">
        <?php foreach ($recentPosts as $post): ?>
            <a href="<?= url('community/topic/' . $post['id']) ?>" class="lesson-item community-link-reset">
                <div>
                    <span class="badge badge-gold dashboard-category-badge"><?= e($post['category']) ?></span>
                </div>
                <div class="lesson-info">
                    <div class="lesson-title"><?= e($post['title']) ?></div>
                    <div class="lesson-meta"><?= e($post['anonymous_name']) ?> &#183; <?= timeAgo($post['created_at']) ?> &#183; <?= $post['comment_count'] ?> comentarios</div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
