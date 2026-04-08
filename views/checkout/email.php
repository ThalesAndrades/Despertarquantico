<?php $pageTitle = 'Finalizar Compra'; ?>
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
    <div class="checkout-page">
        <div class="checkout-card">
            <div class="checkout-product">
                <h2><?= e($product['title']) ?></h2>
                <div class="checkout-price">R$ <?= number_format($product['price'], 2, ',', '.') ?></div>
            </div>

            <form method="POST" action="<?= url('checkout/' . e($product['slug'])) ?>">
                <?= CSRF::field() ?>
                <div class="form-group" style="text-align:left;">
                    <label for="email">Seu e-mail para receber o acesso</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="seu@email.com" required>
                </div>
                <button type="submit" class="checkout-submit">Continuar para o pagamento</button>
            </form>

            <p class="checkout-secure">&#128274; Pagamento seguro via Stripe. Seus dados estao protegidos.</p>
            <p style="margin-top:20px;"><a href="<?= url('') ?>" style="color:var(--gold);font-size:13px;">&#8592; Voltar</a></p>
        </div>
    </div>
</body>
</html>
