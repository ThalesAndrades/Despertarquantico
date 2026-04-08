<a href="<?= url('admin/products') ?>" style="color:#86868B;font-size:14px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:20px;">
    ← Voltar para produtos
</a>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<div style="background:#fff;border-radius:20px;padding:36px;box-shadow:0 1px 3px rgba(0,0,0,0.04);max-width:700px;">
    <form method="POST" action="<?= url($product ? 'admin/products/edit/' . $product['id'] : 'admin/products/create') ?>" enctype="multipart/form-data">
        <?= CSRF::field() ?>

        <div class="form-group">
            <label for="title">Título do Produto</label>
            <input type="text" id="title" name="title" class="form-control" required
                   value="<?= e($product['title'] ?? '') ?>" placeholder="Ex: Mulher Espiral - Despertar Quântico">
        </div>

        <div class="form-group">
            <label for="short_description">Descrição Curta</label>
            <input type="text" id="short_description" name="short_description" class="form-control" maxlength="500"
                   value="<?= e($product['short_description'] ?? '') ?>" placeholder="Uma frase sobre o produto">
        </div>

        <div class="form-group">
            <label for="description">Descrição Completa</label>
            <textarea id="description" name="description" class="form-control" style="min-height:150px;"><?= e($product['description'] ?? '') ?></textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
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

        <div class="form-group" style="margin-top:8px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="checkbox" name="is_active" value="1" <?= (!$product || $product['is_active']) ? 'checked' : '' ?>>
                Produto ativo (visível)
            </label>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" class="btn btn-primary"><?= $product ? 'Salvar Alterações' : 'Criar Produto' ?></button>
            <?php if ($product): ?>
                <a href="<?= url('admin/products/' . $product['id'] . '/content') ?>" class="btn btn-outline">Gerenciar Conteúdo</a>
            <?php endif; ?>
        </div>
    </form>
</div>
