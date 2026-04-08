<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<div style="max-width:700px;">
    <a href="<?= url('community') ?>" style="color:var(--text-muted);font-size:14px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:20px;">
        &#8592; Voltar para a comunidade
    </a>

    <div style="background:var(--bg-card);border-radius:20px;padding:36px;border:1px solid var(--border-subtle);">
        <h2 style="font-family:var(--font-body);font-size:22px;font-weight:700;color:#fff;margin-bottom:24px;">
            Criar novo topico
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
                <label for="title">Titulo</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Sobre o que voce quer falar?" required maxlength="200" value="<?= old('title') ?>">
            </div>

            <div class="form-group">
                <label for="body">Mensagem</label>
                <textarea id="body" name="body" class="form-control" placeholder="Compartilhe seus pensamentos, duvidas ou conquistas..." required style="min-height:180px;"><?= old('body') ?></textarea>
                <p style="font-size:12px;color:var(--text-muted);margin-top:6px;">Sua publicacao aparecera com seu pseudonimo: <strong style="color:var(--gold);"><?= e(currentUser()['anonymous_name'] ?? '') ?></strong></p>
            </div>

            <button type="submit" class="btn btn-primary">Publicar</button>
        </form>
    </div>
</div>
