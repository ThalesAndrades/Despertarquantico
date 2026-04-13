<div class="section-header">
    <h2>Produtos</h2>
    <a href="<?= url('admin/products/create') ?>" class="btn btn-primary btn-sm">+ Novo Produto</a>
</div>

<?php if (empty($products)): ?>
    <div class="empty-state">
        <div class="empty-icon">&#10022;</div>
        <h3 class="empty-title">Nenhum produto</h3>
        <p class="empty-text">Crie seu primeiro produto digital.</p>
        <a href="<?= url('admin/products/create') ?>" class="btn btn-primary">Criar Produto</a>
    </div>
<?php else: ?>
    <div class="table-responsive table-card">
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
                            <div class="admin-product-cell">
                                <div class="admin-product-thumb">
                                    <?= e(mb_substr($prod['title'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= e($prod['title']) ?></div>
                                    <div class="text-muted text-xs">/<?= e($prod['slug']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="fw-semibold">R$ <?= number_format($prod['price'], 2, ',', '.') ?></td>
                        <td><?= $prod['student_count'] ?></td>
                        <td>
                            <span class="badge <?= $prod['is_active'] ? 'badge-green' : 'badge-red' ?>">
                                <?= $prod['is_active'] ? 'Ativo' : 'Inativo' ?>
                            </span>
                        </td>
                        <td>
                            <div class="admin-actions-row">
                                <a href="<?= url('admin/products/edit/' . $prod['id']) ?>" class="btn btn-sm btn-outline">Editar</a>
                                <a href="<?= url('admin/products/' . $prod['id'] . '/content') ?>" class="btn btn-sm btn-primary">Conteúdo</a>
                                <form method="POST" action="<?= url('admin/products/delete/' . $prod['id']) ?>" class="inline-form">
                                    <?= CSRF::field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger" data-confirm="Tem certeza? Isso excluirá o produto e todo seu conteúdo.">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
