<?php
// ==============================================
// File: backend/models/Follow.php
// ==============================================

// ==============================================
// Follow model (English)
// Handles follow-related database operations
// ==============================================

// ==============================================
// مدل فالو (فارسی)
// مدیریت عملیات دیتابیس مرتبط با دنبال کردن کاربران
// ==============================================

require_once __DIR__ . '/../config/database.php';

class Follow {

    private $conn;
    private $table_name = 'follows';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // EN: Follow a user
    // FA: دنبال کردن یک کاربر
    public function follow($follower_id, $following_id) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table_name} (follower_id, following_id) VALUES (:follower_id, :following_id)");
        return $stmt->execute([':follower_id'=>$follower_id, ':following_id'=>$following_id]);
    }

    // EN: Unfollow a user
    // FA: لغو دنبال کردن یک کاربر
    public function unfollow($follower_id, $following_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE follower_id=:follower_id AND following_id=:following_id");
        return $stmt->execute([':follower_id'=>$follower_id, ':following_id'=>$following_id]);
    }

    // EN: Check if following
    // FA: بررسی اینکه آیا کاربر دنبال می‌کند
    public function isFollowing($follower_id, $following_id) {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table_name} WHERE follower_id=:follower_id AND following_id=:following_id LIMIT 1");
        $stmt->execute([':follower_id'=>$follower_id, ':following_id'=>$following_id]);
        return $stmt->fetch() ? true : false;
    }

    // EN: Count followers of a user
    // FA: تعداد دنبال‌کننده‌های یک کاربر
    public function countFollowers($user_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM {$this->table_name} WHERE following_id=:user_id");
        $stmt->execute([':user_id'=>$user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total'] : 0;
    }

    // EN: Count following of a user
    // FA: تعداد کاربرانی که یک کاربر دنبال می‌کند
    public function countFollowing($user_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM {$this->table_name} WHERE follower_id=:user_id");
        $stmt->execute([':user_id'=>$user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total'] : 0;
    }
}