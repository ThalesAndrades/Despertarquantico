<?php
$leadId = (int) ($lead['id'] ?? 0);
$tagsBySlug = [];
foreach ($tags as $t) {
    $tagsBySlug[$t['slug']] = true;
}
?>

<div class="admin-actions" style="margin-bottom:18px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
    <a class="btn btn-outline btn-sm" href="<?= url('admin/leads') ?>">← Voltar</a>
    <span class="badge badge-gold">Score <?= (int) $lead['score'] ?></span>
    <?php if (!empty($lead['pain_primary'])): ?><span class="badge badge-purple"><?= e($lead['pain_primary']) ?></span><?php endif; ?>
    <?php if (!empty($lead['stage'])): ?><span class="badge badge-green"><?= e($lead['stage']) ?></span><?php endif; ?>
    <?php if (!empty($lead['source'])): ?><span class="badge badge-muted"><?= e($lead['source']) ?></span><?php endif; ?>
</div>

<div class="grid" style="display:grid;grid-template-columns:1fr;gap:14px;">
    <div class="card" style="padding:18px;border-radius:14px;">
        <h3 style="margin:0 0 10px;">Contato</h3>
        <div class="text-muted text-sm" style="display:grid;gap:6px;">
            <div><strong>Nome:</strong> <?= e($lead['name'] ?: '-') ?></div>
            <div><strong>E-mail:</strong> <?= e($lead['email']) ?></div>
            <div><strong>WhatsApp:</strong> <?= e($lead['whatsapp'] ?: '-') ?></div>
            <div><strong>Arquétipo social:</strong> <?= e($lead['social_archetype'] ?: '-') ?></div>
            <div><strong>UTM:</strong> <?= e($lead['utm_source'] ?: '-') ?> / <?= e($lead['utm_medium'] ?: '-') ?> / <?= e($lead['utm_campaign'] ?: '-') ?></div>
            <div><strong>Último evento:</strong> <?= e($lead['last_event'] ?: '-') ?><?= !empty($lead['last_event_at']) ? (' • ' . date('d/m/Y H:i', strtotime($lead['last_event_at']))) : '' ?></div>
        </div>
    </div>

    <div class="card" style="padding:18px;border-radius:14px;">
        <h3 style="margin:0 0 10px;">Tags</h3>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
            <?php if (empty($tags)): ?>
                <span class="text-muted text-sm">Nenhuma tag ainda.</span>
            <?php else: ?>
                <?php foreach ($tags as $t): ?>
                    <span class="badge badge-muted"><?= e($t['name']) ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <form method="post" action="<?= url('admin/leads/' . $leadId . '/tag') ?>" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <?= CSRF::field() ?>
            <select class="form-control" name="tag" style="max-width:320px;">
                <option value="">Selecionar tag</option>
                <?php foreach ($tagOptions as $t): ?>
                    <option value="<?= e($t['slug']) ?>" <?= isset($tagsBySlug[$t['slug']]) ? 'disabled' : '' ?>><?= e($t['name']) ?><?= isset($tagsBySlug[$t['slug']]) ? ' (já)' : '' ?></option>
                <?php endforeach; ?>
            </select>
            <input class="form-control" name="tag_custom" placeholder="ou digite slug (ex: dor:ansiedade)" style="max-width:320px;">
            <button type="submit" class="btn btn-gold btn-sm">Aplicar tag</button>
        </form>
    </div>

    <div class="card" style="padding:18px;border-radius:14px;">
        <h3 style="margin:0 0 10px;">Notas</h3>
        <form method="post" action="<?= url('admin/leads/' . $leadId . '/note') ?>" style="display:grid;gap:10px;">
            <?= CSRF::field() ?>
            <textarea class="form-control" name="note" placeholder="Adicionar nota..." required></textarea>
            <div><button class="btn btn-gold btn-sm" type="submit">Salvar nota</button></div>
        </form>

        <div style="margin-top:14px;display:grid;gap:10px;">
            <?php if (empty($notes)): ?>
                <div class="text-muted text-sm">Nenhuma nota ainda.</div>
            <?php else: ?>
                <?php foreach ($notes as $n): ?>
                    <div class="card" style="padding:12px;border-radius:12px;">
                        <div class="text-muted text-sm" style="margin-bottom:6px;">
                            <?= e($n['admin_name'] ?: 'Admin') ?> • <?= date('d/m/Y H:i', strtotime($n['created_at'])) ?>
                        </div>
                        <div><?= nl2br(e($n['note'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card" style="padding:18px;border-radius:14px;">
        <h3 style="margin:0 0 10px;">Timeline</h3>
        <?php if (empty($events)): ?>
            <div class="text-muted text-sm">Sem eventos ainda.</div>
        <?php else: ?>
            <div style="display:grid;gap:10px;">
                <?php foreach ($events as $ev): ?>
                    <div class="card" style="padding:12px;border-radius:12px;">
                        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                            <span class="badge badge-gold"><?= e($ev['event_name']) ?></span>
                            <span class="text-muted text-sm"><?= date('d/m/Y H:i', strtotime($ev['created_at'])) ?></span>
                        </div>
                        <?php if (!empty($ev['properties_json'])): ?>
                            <pre style="white-space:pre-wrap;word-break:break-word;margin:10px 0 0;font-size:12px;color:#bdbdbd;background:#0b0b0b;border:1px solid rgba(255,255,255,0.08);padding:10px;border-radius:12px;"><?= e($ev['properties_json']) ?></pre>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

