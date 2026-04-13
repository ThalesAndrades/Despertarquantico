<!DOCTYPE html>
<?php
$appName = defined('APP_NAME') ? (string) APP_NAME : 'Despertar Espiral';
$appUrl = defined('APP_URL') ? (string) APP_URL : '/';
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro interno | <?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:#0A0A0A;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:20px}
        .wrap{max-width:520px}
        .code{font-family:'Playfair Display',serif;font-size:clamp(80px,15vw,140px);font-weight:600;color:#C9A84C;line-height:1;margin-bottom:16px;opacity:0.8}
        h1{font-family:'Playfair Display',serif;font-size:clamp(20px,3vw,28px);font-weight:600;margin-bottom:12px;color:#fff}
        p{color:rgba(255,255,255,0.40);font-size:15px;line-height:1.7;margin-bottom:32px}
        a{display:inline-block;padding:14px 36px;background:linear-gradient(135deg,#C9A84C,#DFC06A);color:#0A0A0A;text-decoration:none;border-radius:10px;font-weight:600;font-size:14px;transition:transform 0.3s,box-shadow 0.3s}
        a:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(201,168,76,0.3)}
        small{display:block;margin-top:20px;color:rgba(255,255,255,0.25);font-size:12px}
    </style>
</head>
<body>
    <div class="wrap">
        <div class="code">500</div>
        <h1>Algo deu errado por aqui</h1>
        <p>Tivemos um contratempo interno ao processar sua solicitacao. Nossa equipe ja foi notificada. Tente novamente em alguns instantes.</p>
        <a href="<?= htmlspecialchars($appUrl, ENT_QUOTES, 'UTF-8') ?>">Voltar ao inicio</a>
        <small>Se o problema persistir, escreva para contato@despertarespiral.com.</small>
    </div>
</body>
</html>
