<?php
/**
 * Simple Mailer using PHP mail() or SMTP via fsockopen
 * For production, configure with Hostinger SMTP
 */
class Mailer
{
    public static function send(string $to, string $subject, string $htmlBody): bool
    {
        $config = require BASE_PATH . '/config/mail.php';

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$config['from_name']} <{$config['from_email']}>\r\n";
        $headers .= "Reply-To: {$config['from_email']}\r\n";

        return mail($to, $subject, $htmlBody, $headers);
    }

    public static function sendPasswordReset(string $to, string $token): bool
    {
        $resetUrl = APP_URL . '/reset-password?token=' . $token;
        $html = '
        <div style="max-width:600px;margin:0 auto;font-family:Arial,sans-serif;color:#333;">
            <div style="background:#6B21A8;padding:30px;text-align:center;">
                <h1 style="color:#fff;margin:0;">Sunyan Nunes</h1>
            </div>
            <div style="padding:30px;background:#fff;">
                <h2>Redefinir sua senha</h2>
                <p>Você solicitou a redefinição da sua senha. Clique no botão abaixo para criar uma nova senha:</p>
                <p style="text-align:center;margin:30px 0;">
                    <a href="' . e($resetUrl) . '" style="background:#6B21A8;color:#fff;padding:14px 30px;
                       text-decoration:none;border-radius:8px;display:inline-block;font-weight:bold;">
                        Redefinir Senha
                    </a>
                </p>
                <p style="color:#666;font-size:14px;">Este link expira em 1 hora. Se você não solicitou esta redefinição, ignore este e-mail.</p>
            </div>
            <div style="padding:20px;text-align:center;color:#999;font-size:12px;">
                &copy; ' . date('Y') . ' Sunyan Nunes. Todos os direitos reservados.
            </div>
        </div>';

        return self::send($to, 'Redefinir sua senha - Despertar Espiral', $html);
    }

    /**
     * Order confirmation — sent from the Asaas webhook after status=paid.
     * This is a minimal fallback; the rich nurture flow lives in Sequenzy.
     */
    public static function sendOrderConfirmation(
        string $to,
        int $orderId,
        string $productTitle,
        string $paymentMethod
    ): bool {
        $dashboardUrl = APP_URL . '/dashboard';
        $methodLabel = match ($paymentMethod) {
            'pix' => 'PIX',
            'credit_card' => 'Cartão de crédito',
            'boleto' => 'Boleto bancário',
            default => 'Pagamento online',
        };

        $html = '
        <div style="max-width:600px;margin:0 auto;font-family:Arial,sans-serif;color:#333;">
            <div style="background:#0A0A0A;padding:30px;text-align:center;">
                <h1 style="color:#C9A84C;margin:0;font-family:Georgia,serif;">Despertar Espiral</h1>
            </div>
            <div style="padding:30px;background:#fff;">
                <h2 style="color:#0A0A0A;">Sua compra foi confirmada ✨</h2>
                <p>Seja muito bem-vinda ao <strong>' . e($productTitle) . '</strong>.</p>
                <p>Pedido <strong>#' . (int) $orderId . '</strong> — pago via ' . e($methodLabel) . '.</p>
                <p>Seu acesso já está liberado. Toque no botão abaixo para entrar na área de membros e começar quando estiver pronta.</p>
                <p style="text-align:center;margin:30px 0;">
                    <a href="' . e($dashboardUrl) . '" style="background:#C9A84C;color:#0A0A0A;padding:14px 30px;
                       text-decoration:none;border-radius:8px;display:inline-block;font-weight:bold;">
                        Acessar minha conta
                    </a>
                </p>
                <p style="color:#666;font-size:14px;">
                    Qualquer dúvida, responda este e-mail ou escreva para
                    <a href="mailto:contato@despertarespiral.com">contato@despertarespiral.com</a>.
                </p>
            </div>
            <div style="padding:20px;text-align:center;color:#999;font-size:12px;">
                &copy; ' . date('Y') . ' Despertar Espiral. Todos os direitos reservados.
            </div>
        </div>';

        return self::send(
            $to,
            'Acesso liberado — ' . $productTitle,
            $html
        );
    }
}
