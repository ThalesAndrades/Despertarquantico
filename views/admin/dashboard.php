<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Usuários</div>
        <div class="stat-value"><?= $totalUsers ?></div>
        <div class="stat-sub">membros cadastrados</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Vendas</div>
        <div class="stat-value"><?= $totalOrders ?></div>
        <div class="stat-sub">pagamentos confirmados</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Receita</div>
        <div class="stat-value">R$ <?= number_format($totalRevenue, 0, ',', '.') ?></div>
        <div class="stat-sub">total recebido</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Comunidade</div>
        <div class="stat-value"><?= $totalPosts ?></div>
        <div class="stat-sub">publicações</div>
    </div>
</div>

<!-- Recent Orders -->
<div class="section-header">
    <h2>Pedidos Recentes</h2>
    <a href="<?= url('admin/orders') ?>" class="btn btn-sm btn-outline">Ver todos</a>
</div>

<?php if (empty($recentOrders)): ?>
    <p class="text-muted">Nenhum pedido ainda.</p>
<?php else: ?>
    <div class="table-responsive" style="background:var(--bg-card);border-radius:16px;overflow:hidden;border:1px solid var(--border-subtle);">
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>E-mail</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td style="font-weight:600;"><?= e($order['product_title']) ?></td>
                        <td><?= e($order['customer_email']) ?></td>
                        <td>R$ <?= number_format($order['amount'], 2, ',', '.') ?></td>
                        <td>
                            <span class="badge <?= $order['status'] === 'paid' ? 'badge-green' : ($order['status'] === 'pending' ? 'badge-gold' : 'badge-red') ?>">
                                <?= e($order['status']) ?>
                            </span>
                        </td>
                        <td class="text-muted text-sm"><?= timeAgo($order['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Recent Users -->
<div class="section-header mt-4">
    <h2>Novos Usuários</h2>
    <a href="<?= url('admin/users') ?>" class="btn btn-sm btn-outline">Ver todos</a>
</div>

<div class="table-responsive" style="background:var(--bg-card);border-radius:16px;overflow:hidden;border:1px solid var(--border-subtle);">
    <table>
        <thead>
            <tr><th>Nome</th><th>E-mail</th><th>Pseudônimo</th><th>Cadastro</th></tr>
        </thead>
        <tbody>
            <?php foreach ($recentUsers as $u): ?>
                <tr>
                    <td style="font-weight:600;"><?= e($u['name']) ?></td>
                    <td><?= e($u['email']) ?></td>
                    <td class="text-muted"><?= e($u['anonymous_name'] ?? '-') ?></td>
                    <td class="text-muted text-sm"><?= timeAgo($u['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
