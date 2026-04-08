<?php
/**
 * Application Configuration
 */

define('APP_NAME', 'Sunyan Nunes');
define('APP_URL', 'https://mulherespiral.shop');
define('APP_ENV', 'production');
define('AUTO_MIGRATE', APP_ENV !== 'production');

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
