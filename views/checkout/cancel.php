<?php $pageTitle = 'Pagamento Cancelado'; ?>
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
        .cancel-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0A0A0A; padding: 20px; position: relative; }
        .cancel-page::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 50% 30%, rgba(201,168,76,0.03) 0%, transparent 70%); pointer-events: none; }
        .cancel-card { background: #161616; border-radius: 20px; padding: 50px 44px; max-width: 480px; width: 100%; border: 1px solid rgba(255,255,255,0.06); box-shadow: 0 20px 60px rgba(0,0,0,0.5); text-align: center; position: relative; }
        .cancel-icon { font-size: 56px; margin-bottom: 20px; opacity: 0.6; }
        .cancel-card h1 { font-family: 'Playfair Display', serif; color: #fff; font-size: 22px; margin-bottom: 16px; }
        .cancel-card p { color: rgba(255,255,255,0.50); line-height: 1.7; margin-bottom: 16px; font-size: 15px; }
    </style>
</head>
<body>
    <div class="cancel-page">
        <div class="cancel-card">
            <div class="cancel-icon">&#8592;</div>
            <h1>Pagamento nao finalizado</h1>
            <p>Parece que voce cancelou o pagamento. Nao se preocupe, nenhuma cobranca foi realizada.</p>
            <p>Se tiver alguma duvida, entre em contato conosco.</p>
            <div style="margin-top: 28px;">
                <a href="<?= url('') ?>" class="btn btn-primary">Voltar ao site</a>
            </div>
        </div>
    </div>
</body>
</html>
