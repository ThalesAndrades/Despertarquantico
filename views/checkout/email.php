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
    <style>
        .checkout-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0A0A0A; padding: 20px; position: relative; }
        .checkout-page::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 50% 30%, rgba(201,168,76,0.05) 0%, transparent 70%); pointer-events: none; }
        .checkout-card { background: #161616; border-radius: 20px; padding: 44px; max-width: 480px; width: 100%; border: 1px solid rgba(201,168,76,0.15); box-shadow: 0 20px 60px rgba(0,0,0,0.5); text-align: center; position: relative; }
        .checkout-product { margin-bottom: 28px; }
        .checkout-product h2 { font-family: 'Playfair Display', serif; color: #fff; font-size: 24px; }
        .checkout-price { font-size: 38px; color: #C9A84C; font-weight: 700; font-family: 'Playfair Display', serif; margin: 12px 0; text-shadow: 0 0 20px rgba(201,168,76,0.15); }
        .form-group { margin-bottom: 18px; text-align: left; }
        .form-group label { display: block; font-weight: 500; color: rgba(255,255,255,0.55); margin-bottom: 6px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.8px; }
        .form-group input { width: 100%; padding: 14px 16px; border: 1.5px solid rgba(255,255,255,0.08); border-radius: 12px; font-size: 15px; box-sizing: border-box; background: #1A1A1A; color: #fff; font-family: 'Inter', sans-serif; }
        .form-group input::placeholder { color: rgba(255,255,255,0.25); }
        .form-group input:focus { outline: none; border-color: #C9A84C; box-shadow: 0 0 0 3px rgba(201,168,76,0.15); }
        .btn-checkout { width: 100%; padding: 16px; background: linear-gradient(135deg, #C9A84C, #DFC06A); color: #0A0A0A; border: none; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s; font-family: 'Inter', sans-serif; }
        .btn-checkout:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(201,168,76,0.25); }
        .secure-note { color: rgba(255,255,255,0.30); font-size: 12px; margin-top: 18px; }
    </style>
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
                <div class="form-group">
                    <label for="email">Seu e-mail para receber o acesso</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                </div>
                <button type="submit" class="btn-checkout">Continuar para o pagamento</button>
            </form>

            <p class="secure-note">&#128274; Pagamento seguro via Stripe. Seus dados estao protegidos.</p>
            <p style="margin-top:20px;"><a href="<?= url('') ?>" style="color:#C9A84C;font-size:13px;">&#8592; Voltar</a></p>
        </div>
    </div>
</body>
</html>
