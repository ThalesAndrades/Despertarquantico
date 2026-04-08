<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Mulher Espiral') ?> - Sunyan Nunes</title>
    <meta name="description" content="<?= e($metaDescription ?? 'Descubra o poder da sua essência feminina com o método Mulher Espiral de Sunyan Nunes. Transformação, autoconhecimento e despertar.') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/landing.css') ?>">
</head>
<body>
    <?= $content ?? '' ?>
    <script src="<?= asset('js/landing.js') ?>"></script>
</body>
</html>
