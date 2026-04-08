<?php $pageTitle = 'Finalizar Compra'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    <meta name="theme-color" content="#0A0A0A" id="themeColorMeta">
    <?= themeInitScript() ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
</head>
<body>
    <div class="checkout-page">
        <div class="checkout-card">
            <div class="checkout-card-toolbar">
                <?= themeToggleButton('theme-toggle theme-toggle-card', 'Modo claro') ?>
            </div>
            <div class="checkout-product">
                <h2><?= e($product['title']) ?></h2>
                <div class="checkout-price">R$ <?= number_format($product['price'], 2, ',', '.') ?></div>
                <p class="checkout-helper">Voce esta a um passo de receber acesso imediato ao programa e a comunidade privada.</p>
            </div>

            <form method="POST" action="<?= url('checkout/' . e($product['slug'])) ?>">
                <?= CSRF::field() ?>
                <div class="form-group text-left">
                    <label for="email">Seu e-mail para receber o acesso</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="seu@email.com" required>
                </div>
                <button type="submit" class="checkout-submit">Ir para o pagamento seguro</button>
            </form>

            <div class="checkout-trust">
                <span>Acesso imediato</span>
                <span class="trust-dot"></span>
                <span>Checkout seguro</span>
                <span class="trust-dot"></span>
                <span>Sem mensalidade</span>
            </div>
            <p class="checkout-secure">&#128274; Pagamento seguro via Stripe. Seus dados estao protegidos.</p>
            <p class="mt-2"><a href="<?= url('') ?>" class="text-sm text-gold">&#8592; Voltar</a></p>
        </div>
    </div>
    <?= themeScriptTag() ?>
</body>
</html>
