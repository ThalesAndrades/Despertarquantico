<?php
$q = trim($_GET['q'] ?? '');
$tag = trim($_GET['tag'] ?? '');
$pain = trim($_GET['pain'] ?? '');
$stage = trim($_GET['stage'] ?? '');
$source = trim($_GET['source'] ?? '');
$minScore = trim($_GET['min_score'] ?? '');
?>

<form method="get" action="<?= url('admin/leads') ?>" class="admin-actions" style="margin-bottom:18px;display:grid;gap:10px;">
    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Buscar por e-mail, nome, WhatsApp" style="max-width:420px;">
        <input class="form-control" name="min_score" value="<?= e($minScore) ?>" placeholder="Score mínimo" style="max-width:160px;">
        <button class="btn btn-gold btn-sm" type="submit">Filtrar</button>
        <a class="btn btn-outline btn-sm" href="<?= url('admin/leads') ?>">Limpar</a>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <select class="form-control" name="tag" style="max-width:280px;">
            <option value="">Tag (todas)</option>
            <?php foreach ($tagOptions as $t): ?>
                <option value="<?= e($t['slug']) ?>" <?= $tag === $t['slug'] ? 'selected' : '' ?>><?= e($t['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <select class="form-control" name="pain" style="max-width:280px;">
            <option value="">Dor (todas)</option>
            <?php foreach ($painOptions as $p): ?>
                <option value="<?= e($p['value']) ?>" <?= $pain === $p['value'] ? 'selected' : '' ?>><?= e($p['value']) ?> (<?= (int) $p['total'] ?>)</option>
            <?php endforeach; ?>
        </select>

        <select class="form-control" name="stage" style="max-width:280px;">
            <option value="">Estágio (todos)</option>
            <?php foreach ($stageOptions as $s): ?>
                <option value="<?= e($s['value']) ?>" <?= $stage === $s['value'] ? 'selected' : '' ?>><?= e($s['value']) ?> (<?= (int) $s['total'] ?>)</option>
            <?php endforeach; ?>
        </select>

        <select class="form-control" name="source" style="max-width:280px;">
            <option value="">Origem (todas)</option>
            <?php foreach ($sourceOptions as $s): ?>
                <option value="<?= e($s['value']) ?>" <?= $source === $s['value'] ? 'selected' : '' ?>><?= e($s['value']) ?> (<?= (int) $s['total'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<?php if (empty($leads)): ?>
    <div class="empty-state">
        <div class="empty-icon">&#10022;</div>
        <h3 class="empty-title">Nenhum lead encontrado</h3>
        <p class="empty-text">Quando eventos e opt-ins chegarem, eles aparecerão aqui.</p>
    </div>
<?php else: ?>
    <div class="table-responsive table-card">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Lead</th>
                <th>Dor / Estágio</th>
                <th>Score</th>
                <th>Último evento</th>
                <th>Atualizado</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($leads as $l): ?>
                <tr>
                    <td class="text-muted">#<?= (int) $l['id'] ?></td>
                    <td>
                        <div class="fw-semibold"><a href="<?= url('admin/leads/' . (int) $l['id']) ?>"><?= e($l['name'] ?: $l['email']) ?></a></div>
                        <div class="text-muted text-sm"><?= e($l['email']) ?><?= !empty($l['whatsapp']) ? (' • ' . e($l['whatsapp'])) : '' ?></div>
                    </td>
                    <td class="text-muted">
                        <?= e($l['pain_primary'] ?: '-') ?> / <?= e($l['stage'] ?: '-') ?>
                    </td>
                    <td>
                        <span class="badge badge-gold"><?= (int) $l['score'] ?></span>
                    </td>
                    <td class="text-muted text-sm"><?= e($l['last_event'] ?: '-') ?></td>
                    <td class="text-muted text-sm">
                        <?= !empty($l['last_event_at']) ? date('d/m/Y H:i', strtotime($l['last_event_at'])) : '-' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

