<?php
// ==============================================
// File: backend/controllers/AdminController.php
// ==============================================

// ==============================================
// AdminController (English)
// Handles user management, content moderation, reports, and analytics.
// ==============================================

// ==============================================
// کنترلر مدیریت (فارسی)
// مدیریت کاربران، محتوا، گزارش‌ها و آمار
// ==============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';

class AdminController {

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

    // EN: List all users
    // FA: لیست تمام کاربران
    public function listUsers() {
        $stmt = $this->conn->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status'=>'ok','users'=>$users]);
    }

    // EN: Delete a user
    // FA: حذف یک کاربر
    public function deleteUser($userId) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=:id");
        $stmt->execute([':id'=>$userId]);
        echo json_encode(['status'=>'ok','message'=>'User deleted']);
    }

    // EN: List all posts
    // FA: لیست تمام پست‌ها
    public function listPosts() {
        $stmt = $this->conn->query("SELECT * FROM posts ORDER BY created_at DESC");
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status'=>'ok','posts'=>$posts]);
    }

    // EN: Delete a post
    // FA: حذف یک پست
    public function deletePost($postId) {
        $stmt = $this->conn->prepare("DELETE FROM posts WHERE id=:id");
        $stmt->execute([':id'=>$postId]);
        echo json_encode(['status'=>'ok','message'=>'Post deleted']);
    }

    // EN: Get basic analytics (counts of users, posts, groups)
    // FA: دریافت آمار پایه (تعداد کاربران، پست‌ها، گروه‌ها)
    public function getAnalytics() {
        $analytics = [];

        $analytics['users'] = $this->conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $analytics['posts'] = $this->conn->query("SELECT COUNT(*) FROM posts")->fetchColumn();
        $analytics['groups'] = $this->conn->query("SELECT COUNT(*) FROM groups")->fetchColumn();

        echo json_encode(['status'=>'ok','analytics'=>$analytics]);
    }
}