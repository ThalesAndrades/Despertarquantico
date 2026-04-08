<a href="<?= url('admin/products') ?>" style="color:var(--text-muted);font-size:14px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:8px;">
    ← Voltar para produtos
</a>

<p class="text-muted mb-3"><?= e($product['title']) ?></p>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= e($success) ?></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:32px;">
    <!-- Add Module -->
    <div style="background:var(--bg-card);border-radius:16px;padding:24px;border:1px solid var(--border-subtle);">
        <h3 style="font-size:15px;font-weight:700;margin-bottom:16px;">Adicionar Módulo</h3>
        <form method="POST" action="<?= url('admin/modules/save') ?>">
            <?= CSRF::field() ?>
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <div class="form-group">
                <input type="text" name="title" class="form-control" placeholder="Nome do módulo" required>
            </div>
            <div class="form-group">
                <input type="number" name="sort_order" class="form-control" placeholder="Ordem" value="<?= count($modules) + 1 ?>" min="0">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Adicionar Módulo</button>
        </form>
    </div>

    <!-- Add Lesson -->
    <div style="background:var(--bg-card);border-radius:16px;padding:24px;border:1px solid var(--border-subtle);">
        <h3 style="font-size:15px;font-weight:700;margin-bottom:16px;">Adicionar Aula</h3>
        <form method="POST" action="<?= url('admin/lessons/save') ?>">
            <?= CSRF::field() ?>
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <div class="form-group">
                <select name="module_id" class="form-control" required>
                    <option value="">Selecione o módulo</option>
                    <?php foreach ($modules as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= e($m['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="title" class="form-control" placeholder="Título da aula" required>
            </div>
            <div class="form-group">
                <select name="content_type" class="form-control">
                    <option value="video">Vídeo (YouTube/Vimeo)</option>
                    <option value="text">Texto</option>
                    <option value="pdf">PDF</option>
                    <option value="audio">Áudio</option>
                </select>
            </div>
            <div class="form-group">
                <input type="url" name="content_url" class="form-control" placeholder="URL do conteúdo (YouTube embed, Vimeo, PDF...)">
            </div>
            <div class="form-group">
                <textarea name="content_body" class="form-control" placeholder="Conteúdo em texto (opcional)" style="min-height:80px;"></textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div class="form-group">
                    <input type="number" name="duration_minutes" class="form-control" placeholder="Duração (min)" min="0" value="0">
                </div>
                <div class="form-group">
                    <input type="number" name="sort_order" class="form-control" placeholder="Ordem" min="0" value="0">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Adicionar Aula</button>
        </form>
    </div>
</div>

<!-- Existing Content -->
<h3 style="font-size:16px;font-weight:700;margin-bottom:16px;">Conteúdo Atual</h3>

<?php if (empty($modules)): ?>
    <p class="text-muted">Nenhum módulo criado ainda. Comece adicionando um módulo acima.</p>
<?php else: ?>
    <?php foreach ($modules as $module): ?>
        <div style="background:var(--bg-card);border-radius:16px;padding:24px;margin-bottom:16px;border:1px solid var(--border-subtle);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h4 style="font-size:16px;font-weight:700;color:#fff;">
                    📁 <?= e($module['title']) ?>
                    <span class="text-muted text-xs" style="font-weight:400;margin-left:8px;">(Ordem: <?= $module['sort_order'] ?>)</span>
                </h4>
            </div>

            <?php if (empty($module['lessons'])): ?>
                <p class="text-muted text-sm">Nenhuma aula neste módulo.</p>
            <?php else: ?>
                <?php foreach ($module['lessons'] as $lesson): ?>
                    <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:10px;background:var(--bg-surface);margin-bottom:6px;">
                        <span class="badge badge-gray"><?= e($lesson['content_type']) ?></span>
                        <span style="flex:1;font-size:14px;font-weight:500;"><?= e($lesson['title']) ?></span>
                        <?php if ($lesson['duration_minutes'] > 0): ?>
                            <span class="text-muted text-xs"><?= $lesson['duration_minutes'] ?> min</span>
                        <?php endif; ?>
                        <form method="POST" action="<?= url('admin/lessons/delete/' . $lesson['id']) ?>" style="display:inline;" onsubmit="return confirm('Excluir esta aula?')">
                            <?= CSRF::field() ?>
                            <button type="submit" class="btn btn-sm btn-danger" style="padding:4px 10px;font-size:11px;">Excluir</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
