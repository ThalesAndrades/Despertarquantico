<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<div style="max-width:700px;">
    <a href="<?= url('community') ?>" class="community-back-link">
        &#8592; Voltar para a comunidade
    </a>

    <div class="create-topic-card">
        <h2 class="create-topic-title">Criar novo topico</h2>

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
                <p class="create-topic-hint">Sua publicacao aparecera com seu pseudonimo: <strong><?= e(currentUser()['anonymous_name'] ?? '') ?></strong></p>
            </div>

            <button type="submit" class="btn btn-primary">Publicar</button>
        </form>
    </div>
</div>
