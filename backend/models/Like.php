<?php
// ==============================================
// File: backend/models/Like.php
// ==============================================

// ==============================================
// Like model (English)
// Handles like-related database operations
// ==============================================

// ==============================================
// مدل لایک (فارسی)
// مدیریت عملیات دیتابیس مرتبط با لایک‌ها
// ==============================================

require_once __DIR__ . '/../config/database.php';

class Like {

    private $conn;
    private $table_name = 'likes';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // EN: Add like to a post
    // FA: اضافه کردن لایک به یک پست
    public function add($user_id, $post_id) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table_name} (user_id, post_id) VALUES (:user_id, :post_id)");
        return $stmt->execute([':user_id'=>$user_id, ':post_id'=>$post_id]);
    }

    // EN: Remove like from a post
    // FA: حذف لایک از یک پست
    public function remove($user_id, $post_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE user_id=:user_id AND post_id=:post_id");
        return $stmt->execute([':user_id'=>$user_id, ':post_id'=>$post_id]);
    }

    // EN: Get number of likes for a post
    // FA: تعداد لایک‌ها برای یک پست
    public function countByPost($post_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM {$this->table_name} WHERE post_id=:post_id");
        $stmt->execute([':post_id'=>$post_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total'] : 0;
    }

    // EN: Check if a user liked a post
    // FA: بررسی اینکه آیا کاربر یک پست را لایک کرده است
    public function isLiked($user_id, $post_id) {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table_name} WHERE user_id=:user_id AND post_id=:post_id LIMIT 1");
        $stmt->execute([':user_id'=>$user_id, ':post_id'=>$post_id]);
        return $stmt->fetch() ? true : false;
    }
}