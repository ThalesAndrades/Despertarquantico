<?php if (empty($orders)): ?>
    <div class="empty-state">
        <div class="empty-icon">&#10022;</div>
        <h3 class="empty-title">Nenhum pedido ainda</h3>
        <p class="empty-text">Os pedidos aparecerão aqui quando clientes realizarem compras.</p>
    </div>
<?php else: ?>
    <div class="table-responsive table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produto</th>
                    <th>Cliente</th>
                    <th>E-mail</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td class="text-muted">#<?= $order['id'] ?></td>
                        <td class="fw-semibold"><?= e($order['product_title']) ?></td>
                        <td><?= e($order['user_name'] ?? '-') ?></td>
                        <td class="text-muted"><?= e($order['customer_email']) ?></td>
                        <td class="fw-semibold">R$ <?= number_format($order['amount'], 2, ',', '.') ?></td>
                        <td>
                            <span class="badge <?= match($order['status']) { 'paid' => 'badge-green', 'pending' => 'badge-gold', 'refunded' => 'badge-purple', default => 'badge-red' } ?>">
                                <?= e(ucfirst($order['status'])) ?>
                            </span>
                        </td>
                        <td class="text-muted text-sm"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
