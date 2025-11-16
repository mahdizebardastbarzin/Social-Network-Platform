<?php
// ==============================================
// File: backend/routes/api.php
// ==============================================

// ==============================================
// API routes (English)
// Minimal router connecting endpoints to controllers
// ==============================================

// ==============================================
// مسیرهای API (فارسی)
// مسیریاب اولیه که نقاط پایانی را به کنترلرها متصل می‌کند
// ==============================================

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/PostController.php';
require_once __DIR__ . '/../controllers/CommentController.php';
require_once __DIR__ . '/../controllers/LikeController.php';
require_once __DIR__ . '/../controllers/FollowController.php';
require_once __DIR__ . '/../controllers/MessageController.php';
require_once __DIR__ . '/../controllers/GroupController.php';
require_once __DIR__ . '/../controllers/NotificationController.php';
require_once __DIR__ . '/../controllers/AdminController.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// EN: Normalize URI for subfolder deployment
// FA: نرمال‌سازی مسیر در صورت قرارگیری پروژه در زیرپوشه
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($base !== '' && strpos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
}
$uri = '/' . trim($uri, '/');

// EN: Basic health check endpoint
// FA: نقطه پایانی اولیه بررسی وضعیت API
if ($uri === '/api/status') {
    echo json_encode(['status' => 'ok', 'message' => 'API is running']);
    exit;
}

// EN: Authentication endpoints
// FA: مسیرهای احراز هویت
if ($uri === '/api/auth/register' && $method === 'POST') {
    AuthController::register();
}

if ($uri === '/api/auth/login' && $method === 'POST') {
    AuthController::login();
}

if ($uri === '/api/auth/logout' && $method === 'POST') {
    AuthController::logout();
}

// EN: User endpoints
// FA: مسیرهای کاربران
if ($uri === '/api/user/profile' && $method === 'GET') {
    UserController::getProfile();
}

// EN: Post endpoints
// FA: مسیرهای پست‌ها
if ($uri === '/api/posts' && $method === 'POST') {
    PostController::createPost();
}

if ($uri === '/api/posts' && $method === 'GET') {
    PostController::getFeed();
}

// EN: If no route matched
// FA: اگر مسیری پیدا نشد
http_response_code(404);
echo json_encode(['status' => 'error', 'message' => 'Not Found']);
