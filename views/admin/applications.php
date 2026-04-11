<?php
$status = trim($_GET['status'] ?? '');
$statuses = [
    '' => 'Todas',
    'new' => 'Novas',
    'contacted' => 'Contactadas',
    'qualified' => 'Qualificadas',
    'unqualified' => 'Não qualificadas',
];
?>

<div class="admin-actions" style="margin-bottom:18px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
    <?php foreach ($statuses as $key => $label): ?>
        <a href="<?= url('admin/applications' . ($key !== '' ? ('?status=' . $key) : '')) ?>"
           class="btn btn-sm <?= $status === $key ? 'btn-gold' : 'btn-outline' ?>">
            <?= e($label) ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if (empty($applications)): ?>
    <div class="empty-state">
        <div class="empty-icon">&#10022;</div>
        <h3 class="empty-title">Nenhuma aplicação ainda</h3>
        <p class="empty-text">As aplicações do processo seletivo aparecerão aqui.</p>
    </div>
<?php else: ?>
    <div class="table-responsive table-card">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>WhatsApp</th>
                <th>Status</th>
                <th>Data</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($applications as $app): ?>
                <tr>
                    <td class="text-muted">#<?= (int) $app['id'] ?></td>
                    <td class="fw-semibold"><?= e($app['name']) ?></td>
                    <td class="text-muted"><?= e($app['email']) ?></td>
                    <td><?= e($app['whatsapp']) ?></td>
                    <td>
                        <span class="badge <?= match($app['status']) { 'new' => 'badge-gold', 'contacted' => 'badge-purple', 'qualified' => 'badge-green', default => 'badge-red' } ?>">
                            <?= e(ucfirst($app['status'])) ?>
                        </span>
                    </td>
                    <td class="text-muted text-sm"><?= date('d/m/Y H:i', strtotime($app['created_at'])) ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5" class="text-muted text-sm">
                        <div style="display:grid;gap:10px;">
                            <div><strong>Momento:</strong> <?= e($app['moment']) ?></div>
                            <div><strong>90 dias:</strong> <?= e($app['goal']) ?></div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

