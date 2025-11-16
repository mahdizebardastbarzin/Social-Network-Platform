<?php
// ==============================================
// File: backend/models/Comment.php
// ==============================================

// ==============================================
// Comment model (English)
// Handles comment-related database operations
// ==============================================

// ==============================================
// مدل کامنت (فارسی)
// مدیریت عملیات دیتابیس مرتبط با کامنت‌ها
// ==============================================

require_once __DIR__ . '/../config/database.php';

class Comment {

    private $conn;
    private $table_name = 'comments';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // EN: Create a new comment
    // FA: ایجاد کامنت جدید
    public function create($user_id, $post_id, $comment) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table_name} (user_id, post_id, comment) VALUES (:user_id, :post_id, :comment)");
        return $stmt->execute([
            ':user_id'=>$user_id,
            ':post_id'=>$post_id,
            ':comment'=>$comment
        ]);
    }

    // EN: Get comments by post ID
    // FA: دریافت تمام کامنت‌ها بر اساس ID پست
    public function getByPostId($post_id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE post_id=:post_id ORDER BY created_at ASC");
        $stmt->execute([':post_id'=>$post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // EN: Delete comment by ID
    // FA: حذف کامنت بر اساس ID
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE id=:id");
        return $stmt->execute([':id'=>$id]);
    }
}