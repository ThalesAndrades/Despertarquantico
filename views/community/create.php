<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<div style="max-width:700px;">
    <a href="<?= url('community') ?>" style="color:#86868B;font-size:14px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:20px;">
        ← Voltar para a comunidade
    </a>

    <div style="background:#fff;border-radius:20px;padding:36px;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <h2 style="font-family:'Inter',sans-serif;font-size:22px;font-weight:700;color:#1D1D1F;margin-bottom:24px;">
            Criar novo tópico
        </h2>

        <form method="POST" action="<?= url('community/new') ?>">
            <?= CSRF::field() ?>

            <div class="form-group">
                <label for="category">Categoria</label>
                <select name="category" id="category" class="form-control">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= e($cat) ?>"><?= e(ucfirst($cat)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="title">Título</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Sobre o que você quer falar?" required maxlength="200" value="<?= old('title') ?>">
            </div>

            <div class="form-group">
                <label for="body">Mensagem</label>
                <textarea id="body" name="body" class="form-control" placeholder="Compartilhe seus pensamentos, dúvidas ou conquistas..." required style="min-height:180px;"><?= old('body') ?></textarea>
                <p style="font-size:12px;color:#86868B;margin-top:6px;">Sua publicação aparecerá com seu pseudônimo: <strong><?= e(currentUser()['anonymous_name'] ?? '') ?></strong></p>
            </div>

            <button type="submit" class="btn btn-primary">Publicar</button>
        </form>
    </div>
</div>
