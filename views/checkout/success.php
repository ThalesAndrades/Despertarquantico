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
    <style>
        .success-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0A0A0A; padding: 20px; position: relative; }
        .success-page::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 50% 30%, rgba(52,199,89,0.04) 0%, transparent 70%); pointer-events: none; }
        .success-card { background: #161616; border-radius: 20px; padding: 50px 44px; max-width: 520px; width: 100%; border: 1px solid rgba(52,199,89,0.15); box-shadow: 0 20px 60px rgba(0,0,0,0.5); text-align: center; position: relative; }
        .success-icon { font-size: 64px; margin-bottom: 20px; }
        .success-card h1 { font-family: 'Playfair Display', serif; color: #5CD67B; font-size: 26px; margin-bottom: 16px; }
        .success-card p { color: rgba(255,255,255,0.60); line-height: 1.7; margin-bottom: 12px; font-size: 15px; }
        .success-product { background: rgba(52,199,89,0.08); border: 1px solid rgba(52,199,89,0.20); border-radius: 14px; padding: 20px; margin: 24px 0; }
        .success-product strong { color: #5CD67B; font-size: 18px; }
        .success-actions { margin-top: 30px; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
    </style>
</head>
<body>
    <div class="success-page">
        <div class="success-card">
            <div class="success-icon">&#10024;</div>
            <h1>Pagamento confirmado!</h1>
            <p>Parabens! Sua jornada de transformacao comeca agora.</p>

            <?php if ($order ?? null): ?>
                <div class="success-product">
                    <strong><?= e($order['product_title']) ?></strong>
                    <p style="margin:5px 0 0;color:#5CD67B;">R$ <?= number_format($order['amount'], 2, ',', '.') ?></p>
                </div>
            <?php endif; ?>

            <p>Para acessar o conteudo, faca login na area de membros com o e-mail usado na compra.</p>

            <div class="success-actions">
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
