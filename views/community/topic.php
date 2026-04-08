<a href="<?= url('community') ?>" style="color:var(--text-muted);font-size:14px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:20px;">
    &#8592; Voltar para a comunidade
</a>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= e($success) ?></div>
<?php endif; ?>

<!-- Post -->
<div style="background:var(--bg-card);border-radius:20px;padding:36px;border:1px solid var(--border-subtle);margin-bottom:24px;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
        <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--gold-dark),var(--gold));display:flex;align-items:center;justify-content:center;color:#0A0A0A;font-weight:700;font-size:20px;">
            <?= e(mb_substr($post['anonymous_name'], 0, 1)) ?>
        </div>
        <div>
            <span style="font-weight:700;font-size:15px;color:#fff;"><?= e($post['anonymous_name']) ?></span>
            <div style="display:flex;align-items:center;gap:8px;">
                <span class="badge badge-gold" style="font-size:10px;"><?= e($post['category']) ?></span>
                <span style="font-size:12px;color:var(--text-muted);"><?= timeAgo($post['created_at']) ?></span>
            </div>
        </div>
    </div>

    <h1 style="font-family:var(--font-body);font-size:24px;font-weight:700;color:#fff;margin-bottom:16px;line-height:1.3;">
        <?= e($post['title']) ?>
    </h1>

    <div style="font-size:15px;color:var(--text-secondary);line-height:1.8;white-space:pre-wrap;"><?= e($post['body']) ?></div>

    <div style="display:flex;align-items:center;gap:16px;margin-top:20px;padding-top:16px;border-top:1px solid rgba(255,255,255,0.06);">
        <form method="POST" action="<?= url('community/like') ?>" style="display:inline;">
            <?= CSRF::field() ?>
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            <input type="hidden" name="redirect" value="community/topic/<?= $post['id'] ?>">
            <button type="submit" style="background:none;border:none;cursor:pointer;font-size:14px;color:<?= $post['user_liked'] ? 'var(--gold)' : 'var(--text-muted)' ?>;display:flex;align-items:center;gap:6px;font-family:inherit;font-weight:500;">
                <?= $post['user_liked'] ? '&#9733;' : '&#9734;' ?> <?= $post['like_count'] ?> curtidas
            </button>
        </form>
        <span style="font-size:14px;color:var(--text-muted);">&#10022; <?= count($comments) ?> comentarios</span>
    </div>
</div>

<!-- Comments -->
<div style="margin-bottom:24px;">
    <h3 style="font-family:var(--font-body);font-size:16px;font-weight:700;color:#fff;margin-bottom:16px;">
        Comentarios (<?= count($comments) ?>)
    </h3>

    <?php if (empty($comments)): ?>
        <p style="color:var(--text-muted);font-size:14px;padding:20px 0;">Nenhum comentario ainda. Seja a primeira a comentar!</p>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <div style="background:var(--bg-card);border-radius:14px;padding:20px;margin-bottom:10px;border:1px solid var(--border-subtle);">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--gold-dark),var(--gold));display:flex;align-items:center;justify-content:center;color:#0A0A0A;font-weight:700;font-size:13px;">
                        <?= e(mb_substr($comment['anonymous_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <span style="font-weight:600;font-size:13px;color:#fff;"><?= e($comment['anonymous_name']) ?></span>
                        <span style="font-size:11px;color:var(--text-muted);margin-left:8px;"><?= timeAgo($comment['created_at']) ?></span>
                    </div>
                </div>
                <div style="font-size:14px;color:var(--text-secondary);line-height:1.7;padding-left:44px;white-space:pre-wrap;"><?= e($comment['body']) ?></div>
                <div style="padding-left:44px;margin-top:8px;">
                    <form method="POST" action="<?= url('community/like') ?>" style="display:inline;">
                        <?= CSRF::field() ?>
                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                        <input type="hidden" name="redirect" value="community/topic/<?= $post['id'] ?>">
                        <button type="submit" style="background:none;border:none;cursor:pointer;font-size:12px;color:<?= $comment['user_liked'] ? 'var(--gold)' : 'var(--text-muted)' ?>;font-family:inherit;">
                            <?= $comment['user_liked'] ? '&#9733;' : '&#9734;' ?> <?= $comment['like_count'] ?>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Comment Form -->
<div style="background:var(--bg-card);border-radius:20px;padding:28px;border:1px solid var(--border-subtle);">
    <h3 style="font-family:var(--font-body);font-size:15px;font-weight:700;color:#fff;margin-bottom:14px;">Escreva um comentario</h3>
    <form method="POST" action="<?= url('community/comment') ?>">
        <?= CSRF::field() ?>
        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
        <div class="form-group">
            <textarea name="body" class="form-control" placeholder="Compartilhe seus pensamentos..." required style="min-height:100px;"></textarea>
            <p style="font-size:11px;color:var(--text-muted);margin-top:4px;">Publicando como: <strong style="color:var(--gold);"><?= e(currentUser()['anonymous_name'] ?? '') ?></strong></p>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Comentar</button>
    </form>
</div>
