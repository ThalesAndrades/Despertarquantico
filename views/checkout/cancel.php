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
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
</head>
<body>
    <div class="result-page result-page-cancel">
        <div class="result-card result-card-cancel">
            <div class="result-icon result-icon-cancel">&#8592;</div>
            <h1>Pagamento nao finalizado</h1>
            <p>Parece que voce cancelou o pagamento. Nao se preocupe, nenhuma cobranca foi realizada.</p>
            <p>Se tiver alguma duvida, entre em contato conosco.</p>
            <div class="result-actions">
                <a href="<?= url('') ?>" class="btn btn-primary">Voltar ao site</a>
            </div>
        </div>
    </div>
</body>
</html>
