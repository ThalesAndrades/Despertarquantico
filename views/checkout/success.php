<?php $pageTitle = 'Pagamento Confirmado'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        .success-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1a0533, #2d1b69); padding: 20px; }
        .success-card { background: #fff; border-radius: 16px; padding: 50px 40px; max-width: 520px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); text-align: center; }
        .success-icon { font-size: 72px; margin-bottom: 20px; }
        .success-card h1 { font-family: 'Georgia', serif; color: #065F46; font-size: 28px; margin-bottom: 16px; }
        .success-card p { color: #555; line-height: 1.7; margin-bottom: 12px; }
        .success-product { background: #F0FDF4; border: 1px solid #A7F3D0; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .success-product strong { color: #065F46; font-size: 18px; }
        .success-actions { margin-top: 30px; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
    </style>
</head>
<body>
    <div class="success-page">
        <div class="success-card">
            <div class="success-icon">✨</div>
            <h1>Pagamento confirmado!</h1>
            <p>Parabéns! Sua jornada de transformação começa agora.</p>

            <?php if ($order ?? null): ?>
                <div class="success-product">
                    <strong><?= e($order['product_title']) ?></strong>
                    <p style="margin:5px 0 0;color:#065F46;">R$ <?= number_format($order['amount'], 2, ',', '.') ?></p>
                </div>
            <?php endif; ?>

            <p>Para acessar o conteúdo, faça login na área de membros com o e-mail usado na compra.</p>

            <div class="success-actions">
                <?php if (isLoggedIn()): ?>
                    <a href="<?= url('dashboard') ?>" class="btn btn-primary">Acessar Meus Produtos</a>
                <?php else: ?>
                    <a href="<?= url('register') ?>" class="btn btn-primary">Criar Minha Conta</a>
                    <a href="<?= url('login') ?>" class="btn btn-outline">Já tenho conta</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
