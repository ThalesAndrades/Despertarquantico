<?php if (empty($posts)): ?>
    <div class="empty-state">
        <div class="empty-icon">💬</div>
        <h3 class="empty-title">Nenhuma publicação</h3>
        <p class="empty-text">As publicações da comunidade aparecerão aqui para moderação.</p>
    </div>
<?php else: ?>
    <div class="table-responsive" style="background:var(--bg-card);border-radius:16px;overflow:hidden;border:1px solid var(--border-subtle);">
        <table>
            <thead>
                <tr>
                    <th>Pseudônimo</th>
                    <th>Nome Real</th>
                    <th>Categoria</th>
                    <th>Título</th>
                    <th>Comentários</th>
                    <th>Visível</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td style="font-weight:600;"><?= e($post['anonymous_name']) ?></td>
                        <td class="text-muted"><?= e($post['real_name']) ?><br><span class="text-xs"><?= e($post['email']) ?></span></td>
                        <td><span class="badge badge-purple"><?= e($post['category']) ?></span></td>
                        <td>
                            <a href="<?= url('community/topic/' . $post['id']) ?>" style="font-weight:500;">
                                <?= e(mb_substr($post['title'], 0, 50)) ?><?= mb_strlen($post['title']) > 50 ? '...' : '' ?>
                            </a>
                        </td>
                        <td class="text-center"><?= $post['comment_count'] ?></td>
                        <td>
                            <span class="badge <?= $post['is_visible'] ? 'badge-green' : 'badge-red' ?>">
                                <?= $post['is_visible'] ? 'Sim' : 'Oculto' ?>
                            </span>
                        </td>
                        <td class="text-muted text-sm"><?= timeAgo($post['created_at']) ?></td>
                        <td>
                            <form method="POST" action="<?= url('admin/community/toggle/' . $post['id']) ?>" style="display:inline;">
                                <?= CSRF::field() ?>
                                <button type="submit" class="btn btn-sm <?= $post['is_visible'] ? 'btn-danger' : 'btn-primary' ?>">
                                    <?= $post['is_visible'] ? 'Ocultar' : 'Mostrar' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
