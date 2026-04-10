<?php
/**
 * Sequenzy Lifecycle Automation Configuration
 *
 * Docs: https://www.sequenzy.com/features/webhooks
 * Each workspace has a unique webhook URL with built-in authentication.
 * Required env var: SEQUENZY_WEBHOOK_URL
 */

return [
    'webhook_url' => Env::get('SEQUENZY_WEBHOOK_URL', ''),
    'enabled' => in_array(strtolower((string) Env::get('SEQUENZY_ENABLED', 'true')), ['1', 'true', 'yes', 'on'], true),
    'timeout' => (int) Env::get('SEQUENZY_TIMEOUT', 3),
    'source' => 'despertarespiral',
];
