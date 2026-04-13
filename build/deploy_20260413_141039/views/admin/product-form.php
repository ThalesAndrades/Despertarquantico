<a href="<?= url('admin/products') ?>" class="admin-back-link admin-back-link-lg">
    &#8592; Voltar para produtos
</a>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<div class="admin-form-wide">
    <form method="POST" action="<?= url($product ? 'admin/products/edit/' . $product['id'] : 'admin/products/create') ?>" enctype="multipart/form-data">
        <?= CSRF::field() ?>

        <div class="form-group">
            <label for="title">Título do Produto</label>
            <input type="text" id="title" name="title" class="form-control" required
                   value="<?= e($product['title'] ?? '') ?>" placeholder="Ex: Mulher Espiral - Despertar Espiral">
        </div>

        <div class="form-group">
            <label for="short_description">Descrição Curta</label>
            <input type="text" id="short_description" name="short_description" class="form-control" maxlength="500"
                   value="<?= e($product['short_description'] ?? '') ?>" placeholder="Uma frase sobre o produto">
        </div>

        <div class="form-group">
            <label for="description">Descrição Completa</label>
            <textarea id="description" name="description" class="form-control"><?= e($product['description'] ?? '') ?></textarea>
        </div>

        <div class="admin-grid-2-sm">
            <div class="form-group">
                <label for="price">Preço (R$)</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" min="0"
                       value="<?= e($product['price'] ?? '0.00') ?>">
            </div>
            <div class="form-group">
                <label for="sort_order">Ordem de Exibição</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" min="0"
                       value="<?= e($product['sort_order'] ?? '0') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="cover_image">Imagem de Capa</label>
            <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/jpeg,image/png,image/webp">
            <?php if (!empty($product['cover_image'])): ?>
                <p class="text-sm text-muted mt-1">Atual: <?= e($product['cover_image']) ?></p>
            <?php endif; ?>
        </div>

        <div class="form-group mt-1">
            <label class="admin-checkbox-label">
                <input type="checkbox" name="is_active" value="1" <?= (!$product || $product['is_active']) ? 'checked' : '' ?>>
                Produto ativo (visível)
            </label>
        </div>

        <div class="admin-actions-inline">
            <button type="submit" class="btn btn-primary"><?= $product ? 'Salvar Alterações' : 'Criar Produto' ?></button>
            <?php if ($product): ?>
                <a href="<?= url('admin/products/' . $product['id'] . '/content') ?>" class="btn btn-outline">Gerenciar Conteúdo</a>
            <?php endif; ?>
        </div>
    </form>
</div>
