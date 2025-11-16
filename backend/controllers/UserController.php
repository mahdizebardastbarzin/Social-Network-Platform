<?php
// ==============================================
// File: backend/controllers/UserController.php
// ==============================================

// ==============================================
// UserController (English)
// Handles user profile actions like view, update, and settings.
// ==============================================

// ==============================================
// کنترلر کاربران (فارسی)
// مدیریت عملیات پروفایل کاربران مانند مشاهده، ویرایش و تنظیمات.
// ==============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';
require_once __DIR__ . '/../helpers/validation_helper.php';

class UserController {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
        if (!$this->conn) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database connection failed. Run create_db.php first.']);
            exit;
        }
    }

    // EN: Get user profile by ID
    // FA: دریافت پروفایل کاربر بر اساس شناسه
    public function getProfile($userId) {
        $stmt = $this->conn->prepare("SELECT id, username, email, profile_image, cover_image, bio, links, privacy_setting FROM users WHERE id=:id");
        $stmt->execute([':id'=>$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['status'=>'error','message'=>'User not found']);
            return;
        }

        echo json_encode(['status'=>'ok','user'=>$user]);
    }

    // EN: Update user profile
    // FA: بروزرسانی پروفایل کاربر
    public function updateProfile($userId, $data) {
        $fields = [];
        $params = [':id'=>$userId];

        if (isset($data['bio'])) {
            $fields[] = 'bio=:bio';
            $params[':bio'] = $data['bio'];
        }
        if (isset($data['links'])) {
            $fields[] = 'links=:links';
            $params[':links'] = $data['links'];
        }
        if (isset($data['privacy_setting']) && in_array($data['privacy_setting'], ['public','private'])) {
            $fields[] = 'privacy_setting=:privacy';
            $params[':privacy'] = $data['privacy_setting'];
        }

        if (empty($fields)) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'No valid fields to update']);
            return;
        }

        $sql = "UPDATE users SET " . implode(',', $fields) . " WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        echo json_encode(['status'=>'ok','message'=>'Profile updated']);
    }

    // EN: Upload profile or cover image placeholder
    // FA: نگارهٔ اولیه آپلود تصویر پروفایل یا کاور
    public function uploadImage($userId, $type, $file) {
        // This will be implemented using upload_helper.php
        echo json_encode(['status'=>'ok','message'=>'Upload placeholder']);
    }
}