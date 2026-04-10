<?php
/**
 * Mail Configuration (transactional fallback)
 *
 * Only used for password reset and order confirmation.
 * Lifecycle/marketing emails are handled by Sequenzy.
 * Reads from .env — no hardcoded secrets.
 */

return [
    'smtp_host' => Env::get('MAIL_HOST', 'smtp.hostinger.com'),
    'smtp_port' => (int) Env::get('MAIL_PORT', 465),
    'smtp_secure' => Env::get('MAIL_SECURE', 'ssl'),
    'smtp_user' => Env::get('MAIL_USER', ''),
    'smtp_pass' => Env::get('MAIL_PASS', ''),
    'from_email' => Env::get('MAIL_FROM_EMAIL', 'noreply@despertarespiral.com'),
    'from_name' => Env::get('MAIL_FROM_NAME', 'Despertar Espiral'),
];
