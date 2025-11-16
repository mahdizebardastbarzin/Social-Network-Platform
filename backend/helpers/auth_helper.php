<?php
// ==============================================
// File: backend/helpers/auth_helper.php
// ==============================================

// ==============================================
// Auth Helper (English)
// Provides authentication utility functions such as
// password hashing, password verification, and
// generating/validating JWT tokens.
// ==============================================

// ==============================================
// کمک‌کننده احراز هویت (فارسی)
// شامل توابع کاربردی برای هش کردن رمز عبور،
// بررسی رمز عبور، و تولید/اعتبارسنجی توکن JWT.
// ==============================================

require_once __DIR__ . '/jwt_helper.php';

class AuthHelper {

    // Hash password using BCRYPT
    // هش کردن رمز عبور با BCRYPT
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    // Verify password
    // بررسی صحت رمز عبور
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    // Generate access token
    // ساخت توکن دسترسی (Access Token)
    public static function generateToken(array $user): string {
        return JWT::encode([
            'user_id' => $user['id'],
            'email'   => $user['email'],
            'role'    => $user['role'] ?? 'user'
        ], 3600); // 1 hour
    }

    // Validate token and return payload or false
    // اعتبارسنجی توکن و بازگشت payload یا false
    public static function validateToken(string $token): array|false {
        return JWT::decode($token);
    }
}
