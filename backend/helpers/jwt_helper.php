<?php
// ==========================================
// File: backend/helpers/jwt_helper.php
// ==========================================

// ==========================================
// JWT Helper (English)
// Provides functions to generate and validate
// JWT tokens using HS256 algorithm.
// ==========================================

// ==========================================
// کمک‌کننده JWT (فارسی)
// این فایل شامل توابع ساخت و اعتبارسنجی توکن‌
// های JWT با الگوریتم HS256 می‌باشد.
// ==========================================

class JWT {

    // Secret key for signing tokens
    // کلید مخفی برای امضای توکن‌ها
    private static string $secret = "CHANGE_THIS_SECRET_KEY";

    // Generate JWT token
    // ساخت توکن JWT
    public static function encode(array $payload, int $expSeconds = 3600): string {
        // Add expiration time
        // افزودن زمان انقضا
        $payload['exp'] = time() + $expSeconds;

        // Encode header
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);
        $base64Header = self::base64UrlEncode($header);

        // Encode payload
        $payloadJson = json_encode($payload);
        $base64Payload = self::base64UrlEncode($payloadJson);

        // Create signature
        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", self::$secret, true);
        $base64Signature = self::base64UrlEncode($signature);

        // Final token format
        return "$base64Header.$base64Payload.$base64Signature";
    }

    // Validate a JWT token and return payload if valid
    // اعتبارسنجی توکن JWT و بازگرداندن payload در صورت صحیح بودن
    public static function decode(string $token): array|false {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false; // invalid token format
        }

        [$base64Header, $base64Payload, $base64Signature] = $parts;

        // Recalculate signature
        $signatureCheck = self::base64UrlEncode(
            hash_hmac('sha256', "$base64Header.$base64Payload", self::$secret, true)
        );

        // Check signature validity
        if (!hash_equals($signatureCheck, $base64Signature)) {
            return false; // signature mismatch
        }

        // Decode payload
        $payload = json_decode(self::base64UrlDecode($base64Payload), true);

        // Expiration check
        if (!isset($payload['exp']) || time() > $payload['exp']) {
            return false; // token expired
        }

        return $payload;
    }

    // Base64 URL Safe Encode
    private static function base64UrlEncode(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // Base64 URL Safe Decode
    private static function base64UrlDecode(string $data): string {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
