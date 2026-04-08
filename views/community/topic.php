<a href="<?= url('community') ?>" class="community-back-link">
    &#8592; Voltar para a comunidade
</a>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= e($success) ?></div>
<?php endif; ?>

<!-- Post -->
<div class="topic-card">
    <div class="topic-author-row">
        <div class="community-avatar community-avatar-lg">
            <?= e(mb_substr($post['anonymous_name'], 0, 1)) ?>
        </div>
        <div>
            <span class="topic-author-name"><?= e($post['anonymous_name']) ?></span>
            <div class="community-post-meta">
                <span class="badge badge-gold badge-xs"><?= e($post['category']) ?></span>
                <span class="time"><?= timeAgo($post['created_at']) ?></span>
            </div>
        </div>
    </div>

    <h1 class="topic-title"><?= e($post['title']) ?></h1>

    <div class="community-post-body-full"><?= e($post['body']) ?></div>

    <div class="topic-actions">
        <form method="POST" action="<?= url('community/like') ?>" class="inline-form">
            <?= CSRF::field() ?>
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            <input type="hidden" name="redirect" value="community/topic/<?= $post['id'] ?>">
            <button type="submit" class="community-action-btn <?= $post['user_liked'] ? 'liked' : '' ?>">
                <?= $post['user_liked'] ? '&#9733;' : '&#9734;' ?> <?= $post['like_count'] ?> curtidas
            </button>
        </form>
        <span class="community-action-link">&#10022; <?= count($comments) ?> comentarios</span>
    </div>
</div>

<!-- Comments -->
<div class="mb-3">
    <h3 class="comments-section-title">
        Comentarios (<?= count($comments) ?>)
    </h3>

    <?php if (empty($comments)): ?>
        <p class="text-muted text-sm p-2">Nenhum comentario ainda. Seja a primeira a comentar!</p>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment-card">
                <div class="comment-header">
                    <div class="community-avatar community-avatar-sm">
                        <?= e(mb_substr($comment['anonymous_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <span class="comment-author"><?= e($comment['anonymous_name']) ?></span>
                        <span class="comment-time"><?= timeAgo($comment['created_at']) ?></span>
                    </div>
                </div>
                <div class="comment-body"><?= e($comment['body']) ?></div>
                <div class="comment-actions">
                    <form method="POST" action="<?= url('community/like') ?>" class="inline-form">
                        <?= CSRF::field() ?>
                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                        <input type="hidden" name="redirect" value="community/topic/<?= $post['id'] ?>">
                        <button type="submit" class="community-action-btn <?= $comment['user_liked'] ? 'liked' : '' ?> text-xs">
                            <?= $comment['user_liked'] ? '&#9733;' : '&#9734;' ?> <?= $comment['like_count'] ?>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Comment Form -->
<div class="comment-form-card">
    <h3 class="comment-form-title">Escreva um comentario</h3>
    <form method="POST" action="<?= url('community/comment') ?>">
        <?= CSRF::field() ?>
        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
        <div class="form-group">
            <textarea name="body" class="form-control" placeholder="Compartilhe seus pensamentos..." required></textarea>
            <p class="comment-form-hint">Publicando como: <strong><?= e(currentUser()['anonymous_name'] ?? '') ?></strong></p>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Comentar</button>
    </form>
</div>
