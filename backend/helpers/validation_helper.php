<?php
// ==============================================
// File: backend/helpers/validation_helper.php
// ==============================================

// ==============================================
// Validation Helper (English)
// Provides utility functions to validate input data
// including email, username, password, and custom rules.
// ==============================================

// ==============================================
// کمک‌کننده اعتبارسنجی (فارسی)
// شامل توابع کاربردی برای اعتبارسنجی داده‌ها
// مانند ایمیل، نام کاربری، رمز عبور و قوانین سفارشی.
// ==============================================

class ValidationHelper {

    // Validate email format
    // اعتبارسنجی فرمت ایمیل
    public static function validateEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Validate username (alphanumeric, 3-20 chars)
    // اعتبارسنجی نام کاربری (حروف و عدد، طول ۳ تا ۲۰)
    public static function validateUsername(string $username): bool {
        return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username) === 1;
    }

    // Validate password (min 6 chars, at least one number and one letter)
    // اعتبارسنجی رمز عبور (حداقل ۶ کاراکتر، حداقل یک عدد و یک حرف)
    public static function validatePassword(string $password): bool {
        return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $password) === 1;
    }

    // Custom validation rule
    // قانون سفارشی
    public static function customRule(string $value, callable $callback): bool {
        return $callback($value);
    }

}