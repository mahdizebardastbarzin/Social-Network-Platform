<?php
// ==============================================
// File: backend/controllers/NotificationController.php
// ==============================================

// ==============================================
// NotificationController (English)
// Handles creating, listing, and marking notifications as read.
// ==============================================

// ==============================================
// کنترلر نوتیفیکیشن‌ها (فارسی)
// مدیریت ایجاد، لیست و علامت‌گذاری نوتیفیکیشن‌ها به‌عنوان خوانده‌شده
// ==============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';

class NotificationController {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
        if (!$this->conn) {
            http_response_code(500);
            echo json_encode(['status'=>'error','message'=>'Database connection failed. Run create_db.php first.']);
            exit;
        }
    }

    // EN: Create a new notification for a user
    // FA: ایجاد یک نوتیفیکیشن جدید برای یک کاربر
    public function createNotification($userId, $type, $content=null) {
        $stmt = $this->conn->prepare("INSERT INTO notifications (user_id, type, content) VALUES (:uid, :type, :content)");
        $stmt->execute([':uid'=>$userId, ':type'=>$type, ':content'=>$content]);

        echo json_encode(['status'=>'ok','message'=>'Notification created','notification_id'=>$this->conn->lastInsertId()]);
    }

    // EN: List notifications for a user
    // FA: لیست نوتیفیکیشن‌ها برای یک کاربر
    public function listNotifications($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM notifications WHERE user_id=:uid ORDER BY created_at DESC");
        $stmt->execute([':uid'=>$userId]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status'=>'ok','notifications'=>$notifications]);
    }

    // EN: Mark a notification as read
    // FA: علامت‌گذاری یک نوتیفیکیشن به‌عنوان خوانده‌شده
    public function markAsRead($notificationId) {
        $stmt = $this->conn->prepare("UPDATE notifications SET is_read=1 WHERE id=:id");
        $stmt->execute([':id'=>$notificationId]);

        echo json_encode(['status'=>'ok','message'=>'Notification marked as read']);
    }
}