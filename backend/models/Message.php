<?php
// ==============================================
// File: backend/models/Message.php
// ==============================================

// ==============================================
// Message model (English)
// Handles private and group message database operations
// ==============================================

// ==============================================
// مدل پیام (فارسی)
// مدیریت عملیات دیتابیس مرتبط با پیام‌ها
// ==============================================

require_once __DIR__ . '/../config/database.php';

class Message {

    private $conn;
    private $table_name = 'messages';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // EN: Send a message from one user to another
    // FA: ارسال پیام از یک کاربر به کاربر دیگر
    public function send($sender_id, $receiver_id, $message) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table_name} (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)");
        return $stmt->execute([':sender_id'=>$sender_id, ':receiver_id'=>$receiver_id, ':message'=>$message]);
    }

    // EN: Get messages between two users
    // FA: دریافت پیام‌ها بین دو کاربر
    public function getConversation($user1_id, $user2_id, $limit = 50) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table_name} 
             WHERE (sender_id=:user1 AND receiver_id=:user2) OR (sender_id=:user2 AND receiver_id=:user1)
             ORDER BY created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':user1', $user1_id, PDO::PARAM_INT);
        $stmt->bindValue(':user2', $user2_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // EN: Delete a message
    // FA: حذف یک پیام
    public function delete($message_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE id=:id");
        return $stmt->execute([':id'=>$message_id]);
    }

    // EN: Get last messages for a user (inbox)
    // FA: دریافت آخرین پیام‌ها برای یک کاربر (این‌باکس)
    public function getInbox($user_id, $limit = 50) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table_name} 
             WHERE receiver_id=:user_id 
             ORDER BY created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}