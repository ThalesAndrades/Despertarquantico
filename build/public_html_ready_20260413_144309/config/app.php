<?php
/**
 * Application Configuration
 *
 * Reads runtime values from environment variables (.env at project root).
 * See .env.example for the full list of keys.
 */

define('APP_NAME', Env::get('APP_NAME', 'Despertar Espiral'));
define('APP_URL', rtrim(Env::get('APP_URL', 'https://despertarespiral.com'), '/'));
define('APP_ENV', Env::get('APP_ENV', 'production'));
define('APP_DEBUG', in_array(strtolower((string) Env::get('APP_DEBUG', 'false')), ['1', 'true', 'yes', 'on'], true));

// Paths
define('BASE_PATH', dirname(__DIR__));
define('VIEWS_PATH', BASE_PATH . '/views');
define('UPLOADS_PATH', BASE_PATH . '/uploads');
define('UPLOADS_URL', APP_URL . '/uploads');

// Session
define('SESSION_LIFETIME', 7200); // 2 hours

// Upload limits
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
