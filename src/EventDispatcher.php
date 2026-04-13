<?php
/**
 * EventDispatcher — lifecycle event facade.
 *
 * Controllers call EventDispatcher::dispatch('order.paid', [...])
 * at insertion points throughout the app. The facade forwards the
 * event to Sequenzy (the current destination) via a lazy singleton.
 *
 * Payload contract:
 *   [
 *     'email' => string,
 *     'attributes' => array (optional — user-level traits),
 *     'properties' => array (optional — event-level data),
 *   ]
 *
 * Failures never propagate — missing email is silently dropped and
 * transport errors are logged via Sequenzy's own error_log() calls.
 * The app continues even if the automation stack is unreachable.
 */
class EventDispatcher
{
    private static ?SequenzyClient $sequenzy = null;

    public static function dispatch(string $event, array $payload): void
    {
        $email = trim((string) ($payload['email'] ?? ''));
        if ($email === '') {
            return;
        }

        $attributes = is_array($payload['attributes'] ?? null) ? $payload['attributes'] : [];
        $properties = is_array($payload['properties'] ?? null) ? $payload['properties'] : [];

        $config = require BASE_PATH . '/config/sequenzy.php';
        $mode = strtolower((string) ($config['transactional_mode'] ?? 'template'));
        
        if ($mode === 'direct') {
            require_once BASE_PATH . '/src/EmailTemplates.php';
            $templateData = [];
            
            switch ($event) {
                case 'user.password_reset_requested':
                    $templateData = EmailTemplates::passwordReset($properties['reset_url'] ?? '');
                    break;
                case 'user.password_reset_completed':
                    $templateData = EmailTemplates::passwordChanged();
                    break;
                case 'product.access_granted':
                    $templateData = EmailTemplates::accessGranted(
                        $properties['product_name'] ?? 'Produto',
                        $properties['order_id'] ?? '',
                        $properties['payment_method'] ?? ''
                    );
                    break;
                case 'order.overdue':
                    $templateData = EmailTemplates::paymentOverdue(
                        $properties['order_id'] ?? '',
                        $properties['invoice_url'] ?? '',
                        $properties['checkout_url'] ?? ''
                    );
                    break;
                case 'order.refunded':
                    $templateData = EmailTemplates::refundConfirmed(
                        $properties['product_name'] ?? 'Produto',
                        $properties['order_id'] ?? ''
                    );
                    break;
            }

            if (!empty($templateData)) {
                $properties['subject'] = $templateData['subject'];
                $properties['body'] = $templateData['body'];
            }
        }

        try {
            require_once BASE_PATH . '/src/MarketingCRM.php';
            $crmProperties = $properties;
            // Nunca persistir tokens/links sensíveis no CRM interno.
            if ($event === 'user.password_reset_requested') {
                unset($crmProperties['reset_url'], $crmProperties['reset_token_prefix']);
            }
            MarketingCRM::track($event, $email, $attributes, $crmProperties);
        } catch (Throwable $e) {
            error_log('EventDispatcher: crm tracking failed: ' . $e->getMessage());
        }

        try {
            self::sequenzy()->send($event, $email, $attributes, $properties);
        } catch (Throwable $e) {
            error_log(sprintf(
                'EventDispatcher: dispatch of %s failed: %s',
                $event,
                $e->getMessage()
            ));
        }
    }

    private static function sequenzy(): SequenzyClient
    {
        if (self::$sequenzy === null) {
            require_once BASE_PATH . '/src/Sequenzy.php';
            self::$sequenzy = new SequenzyClient();
        }
        return self::$sequenzy;
    }
}
