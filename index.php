<?php
/**
 * Despertar Espiral - Front Controller
 */

// Load .env before any config so env() is available everywhere downstream.
require_once __DIR__ . '/src/Env.php';
Env::load(__DIR__ . '/.env');

require_once __DIR__ . '/config/app.php';

// Error reporting (lighter in production to reduce noisy I/O on shared hosting)
error_reporting(APP_ENV === 'production' ? (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT) : E_ALL);
ini_set('display_errors', APP_ENV === 'production' ? '0' : '1');
ini_set('log_errors', '1');
if (!is_dir(__DIR__ . '/storage/logs')) {
    @mkdir(__DIR__ . '/storage/logs', 0775, true);
}
ini_set('error_log', __DIR__ . '/storage/logs/error.log');

// Global error/exception handler — renders views/errors/500.php in production
// and logs the full context for debugging.
set_exception_handler(function (Throwable $e): void {
    error_log(sprintf(
        "[UNCAUGHT] %s: %s in %s:%d\n%s",
        get_class($e),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    ));
    if (!headers_sent()) {
        http_response_code(500);
    }
    $errorPage = __DIR__ . '/views/errors/500.php';
    if (is_file($errorPage)) {
        require $errorPage;
    } else {
        echo '<h1>Erro interno do servidor</h1>';
    }
    exit;
});
set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
$method = $_SERVER['REQUEST_METHOD'];

function routeRequiresSession(string $url): bool
{
    static $publicStatelessRoutes = [
        '',
        'marketplace',
        'checkout/success',
        'checkout/cancel',
        'webhook/asaas',
    ];

    if (in_array($url, $publicStatelessRoutes, true)) {
        return false;
    }

    if (strpos($url, 'marketplace/') === 0) {
        return false;
    }

    return true;
}

// Session configuration
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', '1');
ini_set('session.gc_maxlifetime', '7200');
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', '1');
}
if (routeRequiresSession($url)) {
    session_start();
}

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Load core files
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/Router.php';
require_once __DIR__ . '/src/CSRF.php';
require_once __DIR__ . '/src/Auth.php';
require_once __DIR__ . '/src/Helpers.php';
require_once __DIR__ . '/src/EventDispatcher.php';

// Load controllers
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/CommunityController.php';
require_once __DIR__ . '/controllers/CheckoutController.php';
require_once __DIR__ . '/controllers/AdminController.php';

// Initialize router
$router = new Router();

// Public routes
$router->get('', [HomeController::class, 'index']);
$router->get('marketplace', [HomeController::class, 'marketplace']);
$router->get('marketplace/{slug}', [HomeController::class, 'marketplaceProduct']);
$router->get('login', [AuthController::class, 'loginForm']);
$router->post('login', [AuthController::class, 'login']);
$router->get('register', [AuthController::class, 'registerForm']);
$router->post('register', [AuthController::class, 'register']);
$router->get('logout', [AuthController::class, 'logout']);
$router->get('forgot-password', [AuthController::class, 'forgotPasswordForm']);
$router->post('forgot-password', [AuthController::class, 'forgotPassword']);
$router->get('reset-password', [AuthController::class, 'resetPasswordForm']);
$router->post('reset-password', [AuthController::class, 'resetPassword']);

// Checkout routes
$router->get('checkout/{slug}', [CheckoutController::class, 'create']);
$router->post('checkout/{slug}', [CheckoutController::class, 'createPost']);
$router->get('checkout/success', [CheckoutController::class, 'success']);
$router->get('checkout/cancel', [CheckoutController::class, 'cancel']);
$router->post('webhook/asaas', [CheckoutController::class, 'webhook']);

// Authenticated routes
$router->get('dashboard', [DashboardController::class, 'index']);
$router->get('products', [ProductController::class, 'index']);
$router->get('products/{slug}', [ProductController::class, 'show']);
$router->get('products/{slug}/lesson/{id}', [ProductController::class, 'lesson']);
$router->post('products/progress', [ProductController::class, 'markProgress']);

// Community routes
$router->get('community', [CommunityController::class, 'index']);
$router->get('community/new', [CommunityController::class, 'createForm']);
$router->post('community/new', [CommunityController::class, 'create']);
$router->get('community/topic/{id}', [CommunityController::class, 'topic']);
$router->post('community/comment', [CommunityController::class, 'comment']);
$router->post('community/like', [CommunityController::class, 'like']);

// Admin routes
$router->get('admin', [AdminController::class, 'dashboard']);
$router->get('admin/users', [AdminController::class, 'users']);
$router->post('admin/users/toggle', [AdminController::class, 'toggleUser']);
$router->post('admin/users/grant-access', [AdminController::class, 'grantAccess']);
$router->get('admin/products', [AdminController::class, 'products']);
$router->get('admin/products/create', [AdminController::class, 'productForm']);
$router->post('admin/products/create', [AdminController::class, 'productSave']);
$router->get('admin/products/edit/{id}', [AdminController::class, 'productForm']);
$router->post('admin/products/edit/{id}', [AdminController::class, 'productSave']);
$router->post('admin/products/delete/{id}', [AdminController::class, 'productDelete']);
$router->get('admin/products/{id}/content', [AdminController::class, 'content']);
$router->post('admin/modules/save', [AdminController::class, 'moduleSave']);
$router->post('admin/lessons/save', [AdminController::class, 'lessonSave']);
$router->post('admin/lessons/delete/{id}', [AdminController::class, 'lessonDelete']);
$router->get('admin/orders', [AdminController::class, 'orders']);
$router->get('admin/community', [AdminController::class, 'community']);
$router->post('admin/community/toggle/{id}', [AdminController::class, 'togglePost']);

// Dispatch
$router->dispatch($method, $url);
