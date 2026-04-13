<?php
/**
 * Sequenzy Lifecycle Automation Configuration
 *
 * Docs: https://www.sequenzy.com/features/webhooks
 * If your dashboard provides a webhook URL, you can use it via SEQUENZY_WEBHOOK_URL.
 * If not, the app can still trigger Sequenzy automations through the API (SEQUENZY_API_KEY).
 */

return [
    'webhook_url' => Env::get('SEQUENZY_WEBHOOK_URL', ''),
    'enabled' => in_array(strtolower((string) Env::get('SEQUENZY_ENABLED', 'true')), ['1', 'true', 'yes', 'on'], true),
    'timeout' => (int) Env::get('SEQUENZY_TIMEOUT', 3),
    'source' => 'despertarespiral',
    'api_url' => rtrim((string) Env::get('SEQUENZY_API_URL', 'https://api.sequenzy.com/api/v1'), '/'),
    'api_key' => (string) Env::get('SEQUENZY_API_KEY', ''),
    'transactional_enabled' => in_array(strtolower((string) Env::get('SEQUENZY_TRANSACTIONAL_ENABLED', 'false')), ['1', 'true', 'yes', 'on'], true),
    'transactional_mode' => strtolower((string) Env::get('SEQUENZY_TRANSACTIONAL_MODE', 'template')),
    'transactional_from' => (string) Env::get('SEQUENZY_TRANSACTIONAL_FROM', ''),
    'transactional_reply_to' => (string) Env::get('SEQUENZY_TRANSACTIONAL_REPLY_TO', ''),
    'transactional_event_slugs' => [
        'user.password_reset_requested' => 'password-reset',
        'user.password_reset_completed' => 'password-changed',
        'product.access_granted' => 'access-granted',
        'order.overdue' => 'payment-overdue',
        'order.refunded' => 'refund-confirmed',
    ],
];
