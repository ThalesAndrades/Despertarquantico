<!-- Search -->
<form method="GET" action="<?= url('admin/users') ?>" class="admin-search-form">
    <input type="text" name="search" class="form-control" placeholder="Buscar por nome, e-mail..." value="<?= e($search) ?>">
    <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
</form>

<p class="text-muted text-sm mb-2"><?= $totalUsers ?> usuários encontrados</p>

<div class="table-responsive table-card">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Pseudônimo</th>
                <th>Status</th>
                <th>Cadastro</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td class="fw-semibold"><?= e($u['name']) ?></td>
                    <td><?= e($u['email']) ?></td>
                    <td class="text-muted"><?= e($u['anonymous_name'] ?? '-') ?></td>
                    <td>
                        <span class="badge <?= $u['is_active'] ? 'badge-green' : 'badge-red' ?>">
                            <?= $u['is_active'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                        <?php if ($u['role'] === 'admin'): ?>
                            <span class="badge badge-purple">Admin</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted text-sm"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                    <td>
                        <div class="admin-actions-row">
                            <?php if ($u['role'] !== 'admin'): ?>
                                <form method="POST" action="<?= url('admin/users/toggle') ?>" class="inline-form">
                                    <?= CSRF::field() ?>
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <button type="submit" class="btn btn-sm <?= $u['is_active'] ? 'btn-danger' : 'btn-primary' ?>">
                                        <?= $u['is_active'] ? 'Desativar' : 'Ativar' ?>
                                    </button>
                                </form>
                                <!-- Grant Access -->
                                <form method="POST" action="<?= url('admin/users/grant-access') ?>" class="admin-grant-form">
                                    <?= CSRF::field() ?>
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <select name="product_id" class="form-control admin-grant-select">
                                        <?php foreach ($products as $prod): ?>
                                            <option value="<?= $prod['id'] ?>"><?= e($prod['title']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline">Conceder</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?= url('admin/users?page=' . $i . ($search ? '&search=' . urlencode($search) : '')) ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>
