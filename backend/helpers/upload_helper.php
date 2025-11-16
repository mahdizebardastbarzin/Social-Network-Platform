<?php
// ==============================================
// File: backend/helpers/upload_helper.php
// ==============================================

// ==============================================
// Upload Helper (English)
// Provides functions to handle file uploads,
// including validation and saving files safely.
// ==============================================

// ==============================================
// کمک‌کننده آپلود (فارسی)
// شامل توابع مدیریت آپلود فایل‌ها،
// اعتبارسنجی و ذخیرهٔ امن فایل‌ها.
// ==============================================

class UploadHelper {

    private static array $allowedTypes = ['image/jpeg','image/png','image/gif'];
    private static int $maxSize = 5 * 1024 * 1024; // 5MB

    // Validate uploaded file
    // اعتبارسنجی فایل آپلود شده
    public static function validateFile(array $file): bool {
        if (!in_array($file['type'], self::$allowedTypes)) {
            return false;
        }
        if ($file['size'] > self::$maxSize) {
            return false;
        }
        return true;
    }

    // Save uploaded file to destination folder
    // ذخیره فایل آپلود شده در پوشهٔ مقصد
    public static function saveFile(array $file, string $destinationFolder): string|false {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('upload_') . '.' . $ext;
        $targetPath = rtrim($destinationFolder, '/') . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $filename;
        }
        return false;
    }

}