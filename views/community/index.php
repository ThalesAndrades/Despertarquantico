<!-- Community Header -->
<div class="section-header">
    <h2>Comunidade Mulher Espiral</h2>
    <a href="<?= url('community/new') ?>" class="btn btn-primary btn-sm">+ Novo Tópico</a>
</div>

<!-- Category Filter -->
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px;">
    <a href="<?= url('community') ?>" class="badge <?= empty($category) ? 'badge-purple' : 'badge-gray' ?>" style="text-decoration:none;padding:8px 16px;font-size:13px;">Todos</a>
    <?php foreach ($categories as $cat): ?>
        <a href="<?= url('community?category=' . $cat) ?>"
           class="badge <?= ($category === $cat) ? 'badge-purple' : 'badge-gray' ?>"
           style="text-decoration:none;padding:8px 16px;font-size:13px;">
            <?= e(ucfirst($cat)) ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if (empty($posts)): ?>
    <div class="empty-state">
        <div class="empty-icon">💬</div>
        <h3 class="empty-title">Nenhum tópico ainda</h3>
        <p class="empty-text">Seja a primeira a compartilhar!</p>
        <a href="<?= url('community/new') ?>" class="btn btn-primary">Criar Tópico</a>
    </div>
<?php else: ?>
    <div style="display:flex;flex-direction:column;gap:10px;">
        <?php foreach ($posts as $post): ?>
            <div class="community-post-card" style="background:#fff;border-radius:16px;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,0.04);transition:all 0.25s;">
                <div style="display:flex;align-items:flex-start;gap:16px;">
                    <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#6B21A8,#D4AF37);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:16px;flex-shrink:0;">
                        <?= e(mb_substr($post['anonymous_name'], 0, 1)) ?>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px;">
                            <span style="font-weight:600;font-size:13px;color:#1D1D1F;"><?= e($post['anonymous_name']) ?></span>
                            <span class="badge badge-purple" style="font-size:10px;"><?= e($post['category']) ?></span>
                            <?php if ($post['is_pinned']): ?>
                                <span class="badge badge-gold" style="font-size:10px;">📌 Fixado</span>
                            <?php endif; ?>
                            <span style="font-size:12px;color:#86868B;"><?= timeAgo($post['created_at']) ?></span>
                        </div>
                        <a href="<?= url('community/topic/' . $post['id']) ?>" style="text-decoration:none;">
                            <h3 style="font-family:'Inter',sans-serif;font-size:17px;font-weight:700;color:#1D1D1F;margin-bottom:6px;line-height:1.3;">
                                <?= e($post['title']) ?>
                            </h3>
                        </a>
                        <p style="font-size:14px;color:#86868B;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            <?= e(mb_substr($post['body'], 0, 180)) ?>
                        </p>
                        <div style="display:flex;align-items:center;gap:16px;margin-top:12px;">
                            <form method="POST" action="<?= url('community/like') ?>" style="display:inline;">
                                <?= CSRF::field() ?>
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <input type="hidden" name="redirect" value="community<?= $category ? '?category=' . e($category) : '' ?>">
                                <button type="submit" style="background:none;border:none;cursor:pointer;font-size:13px;color:<?= $post['user_liked'] ? '#6B21A8' : '#86868B' ?>;display:flex;align-items:center;gap:4px;font-family:inherit;">
                                    <?= $post['user_liked'] ? '💜' : '🤍' ?> <?= $post['like_count'] ?>
                                </button>
                            </form>
                            <a href="<?= url('community/topic/' . $post['id']) ?>" style="font-size:13px;color:#86868B;text-decoration:none;display:flex;align-items:center;gap:4px;">
                                💬 <?= $post['comment_count'] ?>
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
