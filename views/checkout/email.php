<?php $pageTitle = 'Finalizar Compra'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        .checkout-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1a0533, #2d1b69); padding: 20px; }
        .checkout-card { background: #fff; border-radius: 16px; padding: 40px; max-width: 480px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); text-align: center; }
        .checkout-product { margin-bottom: 24px; }
        .checkout-product h2 { font-family: 'Georgia', serif; color: #1a0533; font-size: 24px; }
        .checkout-price { font-size: 36px; color: #D4AF37; font-weight: 700; font-family: 'Georgia', serif; margin: 10px 0; }
        .form-group { margin-bottom: 18px; text-align: left; }
        .form-group label { display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 14px; }
        .form-group input { width: 100%; padding: 14px 16px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 16px; box-sizing: border-box; }
        .form-group input:focus { outline: none; border-color: #6B21A8; }
        .btn-gold { width: 100%; padding: 16px; background: linear-gradient(135deg, #D4AF37, #F4D03F); color: #1a0533; border: none; border-radius: 10px; font-size: 17px; font-weight: 700; cursor: pointer; transition: all 0.3s; }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(212,175,55,0.4); }
        .secure-note { color: #888; font-size: 12px; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="checkout-page">
        <div class="checkout-card">
            <div class="checkout-product">
                <h2><?= e($product['title']) ?></h2>
                <div class="checkout-price">R$ <?= number_format($product['price'], 2, ',', '.') ?></div>
            </div>

            <form method="GET" action="<?= url('checkout/' . e($product['slug'])) ?>">
                <div class="form-group">
                    <label for="email">Seu e-mail para receber o acesso</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                </div>
                <button type="submit" class="btn-gold">Continuar para o pagamento</button>
            </form>

            <p class="secure-note">🔒 Pagamento seguro via Stripe. Seus dados estão protegidos.</p>
            <p style="margin-top:20px;"><a href="<?= url('') ?>" style="color:#6B21A8;font-size:14px;">← Voltar</a></p>
        </div>
    </div>
</body>
</html>
