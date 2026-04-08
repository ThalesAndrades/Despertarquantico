<?php $pageTitle = 'Pagamento Cancelado'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        .cancel-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1a0533, #2d1b69); padding: 20px; }
        .cancel-card { background: #fff; border-radius: 16px; padding: 50px 40px; max-width: 480px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); text-align: center; }
        .cancel-icon { font-size: 64px; margin-bottom: 20px; }
        .cancel-card h1 { font-family: 'Georgia', serif; color: #333; font-size: 24px; margin-bottom: 16px; }
        .cancel-card p { color: #666; line-height: 1.7; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="cancel-page">
        <div class="cancel-card">
            <div class="cancel-icon">🔙</div>
            <h1>Pagamento não finalizado</h1>
            <p>Parece que você cancelou o pagamento. Não se preocupe, nenhuma cobrança foi realizada.</p>
            <p>Se tiver alguma dúvida, entre em contato conosco.</p>
            <div style="margin-top: 24px;">
                <a href="<?= url('') ?>" class="btn btn-primary">Voltar ao site</a>
            </div>
        </div>
    </div>
</body>
</html>
