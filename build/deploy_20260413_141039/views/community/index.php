<!-- Community Header -->
<div class="section-header">
    <h2>Comunidade Mulher Espiral</h2>
    <a href="<?= url('community/new') ?>" class="btn btn-primary btn-sm">+ Novo Topico</a>
</div>

<!-- Category Filter -->
<div class="community-category-filter">
    <a href="<?= url('community') ?>" class="badge <?= empty($category) ? 'badge-gold' : 'badge-gray' ?>">Todos</a>
    <?php foreach ($categories as $cat): ?>
        <a href="<?= url('community?category=' . $cat) ?>"
           class="badge <?= ($category === $cat) ? 'badge-gold' : 'badge-gray' ?>">
            <?= e(ucfirst($cat)) ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if (empty($posts)): ?>
    <div class="empty-state">
        <div class="empty-icon">&#10022;</div>
        <h3 class="empty-title">Nenhum topico ainda</h3>
        <p class="empty-text">Seja a primeira a compartilhar!</p>
        <a href="<?= url('community/new') ?>" class="btn btn-primary">Criar Topico</a>
    </div>
<?php else: ?>
    <div class="community-post-list">
        <?php foreach ($posts as $post): ?>
            <div class="community-post-card">
                <div class="community-post-layout">
                    <div class="community-avatar">
                        <?= e(mb_substr($post['anonymous_name'], 0, 1)) ?>
                    </div>
                    <div class="community-post-content-wrap">
                        <div class="community-post-meta">
                            <span class="author"><?= e($post['anonymous_name']) ?></span>
                            <span class="badge badge-gold badge-xs"><?= e($post['category']) ?></span>
                            <?php if ($post['is_pinned']): ?>
                                <span class="badge badge-gold badge-xs">&#128204; Fixado</span>
                            <?php endif; ?>
                            <span class="time"><?= timeAgo($post['created_at']) ?></span>
                        </div>
                        <a href="<?= url('community/topic/' . $post['id']) ?>" class="community-post-title">
                            <?= e($post['title']) ?>
                        </a>
                        <p class="community-post-body">
                            <?= e(mb_substr($post['body'], 0, 180)) ?>
                        </p>
                        <div class="community-actions">
                            <form method="POST" action="<?= url('community/like') ?>" class="inline-form">
                                <?= CSRF::field() ?>
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <input type="hidden" name="redirect" value="community<?= $category ? '?category=' . e($category) : '' ?>">
                                <button type="submit" class="community-action-btn <?= $post['user_liked'] ? 'liked' : '' ?>">
                                    <?= $post['user_liked'] ? '&#9733;' : '&#9734;' ?> <?= $post['like_count'] ?>
                                </button>
                            </form>
                            <a href="<?= url('community/topic/' . $post['id']) ?>" class="community-action-link">
                                &#10022; <?= $post['comment_count'] ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="<?= url('community?page=' . $i . ($category ? '&category=' . e($category) : '')) ?>"
                   class="<?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
