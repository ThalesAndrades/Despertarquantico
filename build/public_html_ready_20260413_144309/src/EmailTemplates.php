<?php
class EmailTemplates
{
    private static function layout(string $title, string $content): string
    {
        $year = date('Y');
        $homeUrl = APP_URL;
        
        return <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap');
        
        body {
            margin: 0;
            padding: 0;
            background-color: #050505;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #E5E5E5;
            -webkit-font-smoothing: antialiased;
        }
        
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #050505;
            padding: 40px 0;
        }
        
        .main {
            background-color: #0A0A0A;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-radius: 16px;
            border: 1px solid #1A1A1A;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        
        .header {
            padding: 40px 40px 20px;
            text-align: center;
        }
        
        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 600;
            color: #C9A84C;
            margin: 0;
            letter-spacing: -0.5px;
            text-decoration: none;
        }
        
        .content {
            padding: 20px 40px 40px;
        }
        
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 400;
            color: #FFFFFF;
            margin: 0 0 20px;
            line-height: 1.3;
        }
        
        p {
            font-size: 15px;
            line-height: 1.6;
            color: #A3A3A3;
            margin: 0 0 20px;
        }
        
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        
        .button {
            display: inline-block;
            background-color: #C9A84C;
            color: #000000 !important;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            transition: background-color 0.2s;
        }
        
        .footer {
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #1A1A1A;
        }
        
        .footer p {
            font-size: 12px;
            color: #666666;
            margin: 0;
        }
        
        .highlight {
            color: #C9A84C;
        }
        
        .box {
            background-color: #111111;
            border: 1px solid #222222;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .box-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666666;
            margin: 0 0 5px;
        }
        
        .box-value {
            font-size: 16px;
            color: #E5E5E5;
            margin: 0;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main" width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td class="header">
                    <a href="{$homeUrl}" class="logo">Despertar Espiral</a>
                </td>
            </tr>
            <tr>
                <td class="content">
                    {$content}
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <p>&copy; {$year} Despertar Espiral. Todos os direitos reservados.</p>
                    <p style="margin-top: 10px;">Esta é uma mensagem automática, por favor não responda.</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
HTML;
    }

    public static function passwordReset(string $resetUrl): array
    {
        $subject = 'Redefinição de Senha';
        $button = '';
        $footnote = '<p style="font-size: 13px; color: #666;">Este link é válido por 2 horas. Após esse período, será necessário solicitar novamente.</p>';
        if ($resetUrl !== '') {
            $button = <<<HTML
            <div class="button-container">
                <a href="{$resetUrl}" class="button">Redefinir minha senha</a>
            </div>
HTML;
        } else {
            $button = '<div class="box"><p class="box-title">Ação necessária</p><p class="box-value">Abra o site e solicite novamente a redefinição de senha.</p></div>';
            $footnote = '';
        }

        $content = <<<HTML
            <h1>Esqueceu sua senha?</h1>
            <p>Recebemos um pedido para redefinir a senha da sua conta no <strong>Despertar Espiral</strong>. Se você não fez essa solicitação, pode ignorar este e-mail em segurança.</p>
            <p>Para criar uma nova senha e recuperar seu acesso, toque no botão abaixo:</p>
            {$button}
            {$footnote}
HTML;

        return [
            'subject' => $subject,
            'body' => self::layout($subject, $content)
        ];
    }

    public static function passwordChanged(): array
    {
        $subject = 'Sua senha foi alterada';
        $loginUrl = APP_URL . '/login';
        $content = <<<HTML
            <h1>Senha alterada com sucesso</h1>
            <p>A senha da sua conta no <strong>Despertar Espiral</strong> acabou de ser atualizada.</p>
            <p>Se foi você quem realizou esta alteração, nenhuma ação adicional é necessária. Você já pode acessar a plataforma com suas novas credenciais.</p>
            
            <div class="button-container">
                <a href="{$loginUrl}" class="button">Acessar a Plataforma</a>
            </div>
            
            <p style="font-size: 13px; color: #666;">Se você não realizou esta alteração, entre em contato imediatamente com nosso suporte respondendo a este e-mail.</p>
HTML;

        return [
            'subject' => $subject,
            'body' => self::layout($subject, $content)
        ];
    }

    public static function accessGranted(string $productName, string $orderId, string $paymentMethod): array
    {
        $subject = 'Acesso Liberado: ' . $productName;
        $dashboardUrl = APP_URL . '/dashboard';
        
        $methodLabel = 'Pagamento online';
        if ($paymentMethod === 'pix') $methodLabel = 'PIX';
        if ($paymentMethod === 'credit_card') $methodLabel = 'Cartão de Crédito';
        if ($paymentMethod === 'boleto') $methodLabel = 'Boleto Bancário';
        
        $content = <<<HTML
            <h1>Seja bem-vinda à sua nova jornada.</h1>
            <p>Seu pagamento foi confirmado e seu acesso ao <strong>{$productName}</strong> já está totalmente liberado e disponível na sua área de membros.</p>
            
            <div class="box">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td width="50%">
                            <p class="box-title">Pedido</p>
                            <p class="box-value">#{$orderId}</p>
                        </td>
                        <td width="50%">
                            <p class="box-title">Pagamento via</p>
                            <p class="box-value">{$methodLabel}</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <p>Prepare-se para uma experiência de transformação profunda. Recomendamos que você acesse a plataforma agora mesmo para conhecer a estrutura do método.</p>
            
            <div class="button-container">
                <a href="{$dashboardUrl}" class="button">Acessar minha conta</a>
            </div>
HTML;

        return [
            'subject' => $subject,
            'body' => self::layout($subject, $content)
        ];
    }

    public static function paymentOverdue(string $orderId, string $invoiceUrl, string $checkoutUrl = ''): array
    {
        $subject = 'Aviso: Pagamento Pendente';

        $ctaUrl = $invoiceUrl !== '' ? $invoiceUrl : $checkoutUrl;
        $cta = '';
        if ($ctaUrl !== '') {
            $cta = <<<HTML
            <div class="button-container">
                <a href="{$ctaUrl}" class="button">Regularizar Pagamento</a>
            </div>
HTML;
        } else {
            $cta = '<div class="box"><p class="box-title">Ação necessária</p><p class="box-value">Acesse o site e refaça o checkout para gerar um novo link de pagamento.</p></div>';
        }
        
        $content = <<<HTML
            <h1>Seu pagamento está pendente</h1>
            <p>Notamos que o pagamento referente ao pedido <strong>#{$orderId}</strong> não foi concluído e encontra-se vencido ou próximo do vencimento.</p>
            <p>Para garantir que você não perca seu acesso ao programa e à comunidade, por favor regularize sua situação através da nossa plataforma de pagamentos segura.</p>
            {$cta}
            
            <p style="font-size: 13px; color: #666;">Se você já efetuou o pagamento via boleto, lembre-se que pode levar até 3 dias úteis para a compensação bancária. Neste caso, desconsidere este e-mail.</p>
HTML;

        return [
            'subject' => $subject,
            'body' => self::layout($subject, $content)
        ];
    }

    public static function refundConfirmed(string $productName, string $orderId): array
    {
        $subject = 'Confirmação de Reembolso';
        
        $content = <<<HTML
            <h1>Reembolso Processado</h1>
            <p>Confirmamos que o reembolso do seu pedido <strong>#{$orderId}</strong> referente ao programa <strong>{$productName}</strong> foi processado com sucesso.</p>
            <p>O valor será devolvido através do mesmo método de pagamento utilizado na compra original. Para pagamentos em cartão de crédito, o crédito pode levar de 1 a 2 faturas para constar no extrato, dependendo da administradora do seu cartão.</p>
            <p>Seu acesso à área de membros e à comunidade foi revogado.</p>
            <p>Agradecemos por ter tentado o nosso método e desejamos muita luz em sua jornada. As portas estarão sempre abertas caso decida retornar no futuro.</p>
HTML;

        return [
            'subject' => $subject,
            'body' => self::layout($subject, $content)
        ];
    }
}
