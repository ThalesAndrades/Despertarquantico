<?php $pageTitle = 'Pagamento Confirmado'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
</head>
<body>
    <div class="result-page result-page-success">
        <div class="result-card result-card-success">
            <div class="result-icon">&#10024;</div>
            <h1>Pagamento confirmado!</h1>
            <p>Parabens! Sua jornada de transformacao comeca agora.</p>

            <?php if ($order ?? null): ?>
                <div class="result-product">
                    <strong><?= e($order['product_title']) ?></strong>
                    <p>R$ <?= number_format($order['amount'], 2, ',', '.') ?></p>
                </div>
            <?php endif; ?>

            <p>Para acessar o conteudo, faca login na area de membros com o e-mail usado na compra.</p>

            <div class="result-actions">
                <?php if (isLoggedIn()): ?>
                    <a href="<?= url('dashboard') ?>" class="btn btn-primary">Acessar Meus Produtos</a>
                <?php else: ?>
                    <a href="<?= url('register') ?>" class="btn btn-primary">Criar Minha Conta</a>
                    <a href="<?= url('login') ?>" class="btn btn-outline">Ja tenho conta</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
