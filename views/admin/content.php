<a href="<?= url('admin/products') ?>" class="admin-back-link">
    &#8592; Voltar para produtos
</a>

<p class="text-muted mb-3"><?= e($product['title']) ?></p>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= e($success) ?></div>
<?php endif; ?>

<div class="admin-grid-2">
    <!-- Add Module -->
    <div class="admin-form-card">
        <h3>Adicionar Módulo</h3>
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
    <div class="admin-form-card">
        <h3>Adicionar Aula</h3>
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
                <textarea name="content_body" class="form-control" placeholder="Conteúdo em texto (opcional)"></textarea>
            </div>
            <div class="admin-grid-2-xs">
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
<h3 class="admin-content-title">Conteúdo Atual</h3>

<?php if (empty($modules)): ?>
    <p class="text-muted">Nenhum módulo criado ainda. Comece adicionando um módulo acima.</p>
<?php else: ?>
    <?php foreach ($modules as $module): ?>
        <div class="admin-module-card">
            <div class="admin-module-header">
                <h4 class="admin-module-title">
                    &#128193; <?= e($module['title']) ?>
                    <span class="text-muted text-xs admin-module-order">(Ordem: <?= $module['sort_order'] ?>)</span>
                </h4>
            </div>

            <?php if (empty($module['lessons'])): ?>
                <p class="text-muted text-sm">Nenhuma aula neste módulo.</p>
            <?php else: ?>
                <?php foreach ($module['lessons'] as $lesson): ?>
                    <div class="admin-lesson-row">
                        <span class="badge badge-gray"><?= e($lesson['content_type']) ?></span>
                        <span class="admin-lesson-name"><?= e($lesson['title']) ?></span>
                        <?php if ($lesson['duration_minutes'] > 0): ?>
                            <span class="text-muted text-xs"><?= $lesson['duration_minutes'] ?> min</span>
                        <?php endif; ?>
                        <form method="POST" action="<?= url('admin/lessons/delete/' . $lesson['id']) ?>" class="inline-form">
                            <?= CSRF::field() ?>
                            <button type="submit" class="btn btn-sm btn-danger btn-xs" data-confirm="Excluir esta aula?">Excluir</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
