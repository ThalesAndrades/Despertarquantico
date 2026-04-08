<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina nao encontrada - Mulher Espiral</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:#0A0A0A;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:20px}
        .wrap{max-width:480px}
        .code{font-family:'Playfair Display',serif;font-size:clamp(80px,15vw,140px);font-weight:600;color:#C9A84C;line-height:1;margin-bottom:16px;opacity:0.8}
        h1{font-family:'Playfair Display',serif;font-size:clamp(20px,3vw,28px);font-weight:600;margin-bottom:12px;color:#fff}
        p{color:rgba(255,255,255,0.40);font-size:15px;line-height:1.7;margin-bottom:32px}
        a{display:inline-block;padding:14px 36px;background:linear-gradient(135deg,#C9A84C,#DFC06A);color:#0A0A0A;text-decoration:none;border-radius:10px;font-weight:600;font-size:14px;transition:transform 0.3s,box-shadow 0.3s}
        a:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(201,168,76,0.3)}
    </style>
</head>
<body>
    <div class="wrap">
        <div class="code">404</div>
        <h1>Pagina nao encontrada</h1>
        <p>O caminho que voce buscou nao existe. Mas sua jornada pode comecar agora.</p>
        <a href="<?= defined('APP_URL') ? APP_URL : '/' ?>">Voltar ao inicio</a>
    </div>
</body>
</html>
