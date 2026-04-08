<?php if (empty($products)): ?>
    <div class="empty-state">
        <div class="empty-icon">&#10022;</div>
        <h3 class="empty-title">Nenhum produto disponivel</h3>
        <p class="empty-text">Voce ainda nao tem acesso a nenhum produto. Explore nosso catalogo!</p>
        <a href="<?= url('') ?>" class="btn btn-primary">Explorar Produtos</a>
    </div>
<?php else: ?>
    <div class="products-grid">
        <?php foreach ($products as $prod): ?>
            <a href="<?= url('products/' . e($prod['slug'])) ?>" class="product-card no-decoration">
                <div class="product-card-img">
                    <?php if ($prod['cover_image']): ?>
                        <img src="<?= url('uploads/' . e($prod['cover_image'])) ?>" alt="<?= e($prod['title']) ?>">
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
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
