<?php
// ==============================================
// File: backend/models/Post.php
// ==============================================

// ==============================================
// Post model (English)
// Handles post-related database operations
// ==============================================

// ==============================================
// مدل پست (فارسی)
// مدیریت عملیات دیتابیس مرتبط با پست‌ها
// ==============================================

require_once __DIR__ . '/../config/database.php';

class Post {

    private $conn;
    private $table_name = 'posts';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // EN: Create a new post
    // FA: ایجاد پست جدید
    public function create($user_id, $content=null, $media_url=null, $type='text') {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table_name} (user_id, content, media_url, type) VALUES (:user_id, :content, :media_url, :type)");
        return $stmt->execute([
            ':user_id'=>$user_id,
            ':content'=>$content,
            ':media_url'=>$media_url,
            ':type'=>$type
        ]);
    }

    // EN: Get post by ID
    // FA: دریافت پست بر اساس ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE id=:id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // EN: Get posts by user ID
    // FA: دریافت تمام پست‌های یک کاربر
    public function getByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE user_id=:user_id ORDER BY created_at DESC");
        $stmt->execute([':user_id'=>$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // EN: Delete post by ID
    // FA: حذف پست بر اساس ID
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE id=:id");
        return $stmt->execute([':id'=>$id]);
    }
}