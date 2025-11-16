<?php
// backend/index.php
// EN: Main entry point for backend API requests.
// FA: نقطهٔ ورود اصلی برای درخواست‌های API بک‌اند.

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/core/App.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Response.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/jwt_helper.php';
require_once __DIR__ . '/helpers/auth_helper.php';
require_once __DIR__ . '/helpers/upload_helper.php';
require_once __DIR__ . '/helpers/validation_helper.php';

$app = new App();

// EN: Include API routes
// FA: بارگذاری مسیرهای API
require_once __DIR__ . '/routes/api.php';