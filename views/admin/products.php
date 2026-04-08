<div class="section-header">
    <h2>Produtos</h2>
    <a href="<?= url('admin/products/create') ?>" class="btn btn-primary btn-sm">+ Novo Produto</a>
</div>

<?php if (empty($products)): ?>
    <div class="empty-state">
        <div class="empty-icon">📦</div>
        <h3 class="empty-title">Nenhum produto</h3>
        <p class="empty-text">Crie seu primeiro produto digital.</p>
        <a href="<?= url('admin/products/create') ?>" class="btn btn-primary">Criar Produto</a>
    </div>
<?php else: ?>
    <div class="table-responsive" style="background:var(--bg-card);border-radius:16px;overflow:hidden;border:1px solid var(--border-subtle);">
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Alunos</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $prod): ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#6B21A8,#D4AF37);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px;flex-shrink:0;">
                                    <?= e(mb_substr($prod['title'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div style="font-weight:600;"><?= e($prod['title']) ?></div>
                                    <div class="text-muted text-xs">/<?= e($prod['slug']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight:600;">R$ <?= number_format($prod['price'], 2, ',', '.') ?></td>
                        <td><?= $prod['student_count'] ?></td>
                        <td>
                            <span class="badge <?= $prod['is_active'] ? 'badge-green' : 'badge-red' ?>">
                                <?= $prod['is_active'] ? 'Ativo' : 'Inativo' ?>
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="<?= url('admin/products/edit/' . $prod['id']) ?>" class="btn btn-sm btn-outline">Editar</a>
                                <a href="<?= url('admin/products/' . $prod['id'] . '/content') ?>" class="btn btn-sm btn-primary">Conteúdo</a>
                                <form method="POST" action="<?= url('admin/products/delete/' . $prod['id']) ?>" style="display:inline;" onsubmit="return confirm('Tem certeza? Isso excluirá o produto e todo seu conteúdo.')">
                                    <?= CSRF::field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
