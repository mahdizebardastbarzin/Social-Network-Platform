<?php
// ==============================================
// File: backend/models/Notification.php
// ==============================================

// ==============================================
// Notification model (English)
// Handles notifications database operations
// ==============================================

// ==============================================
// مدل اعلان‌ها (فارسی)
// مدیریت عملیات دیتابیس مرتبط با اعلان‌ها
// ==============================================

require_once __DIR__ . '/../config/database.php';

class Notification {

    private $conn;
    private $table_name = 'notifications';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // EN: Create a new notification
    // FA: ایجاد یک اعلان جدید
    public function create($user_id, $type, $content = null) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table_name} (user_id, type, content) VALUES (:user_id, :type, :content)");
        return $stmt->execute([':user_id'=>$user_id, ':type'=>$type, ':content'=>$content]);
    }

    // EN: Get all notifications for a user
    // FA: دریافت تمام اعلان‌ها برای یک کاربر
    public function getByUser($user_id, $limit = 50) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE user_id=:user_id ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // EN: Mark notification as read
    // FA: علامت‌گذاری اعلان به‌عنوان خوانده شده
    public function markAsRead($notification_id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table_name} SET is_read=1 WHERE id=:id");
        return $stmt->execute([':id'=>$notification_id]);
    }

    // EN: Delete a notification
    // FA: حذف یک اعلان
    public function delete($notification_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE id=:id");
        return $stmt->execute([':id'=>$notification_id]);
    }
}