<?php
// ==============================================
// File: backend/core/Response.php
// ==============================================

// ==============================================
// Core Response class (English)
// Provides helper methods for sending JSON responses
// ==============================================

// ==============================================
// کلاس پاسخ‌دهی اصلی (فارسی)
// ارائه متدهای کمکی برای ارسال پاسخ‌های JSON
// ==============================================

class Response {
    // EN: Send a success JSON response
    // FA: ارسال پاسخ موفقیت‌آمیز به صورت JSON
    public static function success($data = [], $message = 'Success', $code = 200) {
        http_response_code($code);
        echo json_encode(['status' => 'ok', 'message' => $message, 'data' => $data]);
        exit;
    }

    // EN: Send an error JSON response
    // FA: ارسال پاسخ خطا به صورت JSON
    public static function error($message = 'Error', $code = 400, $data = []) {
        http_response_code($code);
        echo json_encode(['status' => 'error', 'message' => $message, 'data' => $data]);
        exit;
    }
}