<?php
// ==============================================
// File: backend/controllers/LikeController.php
// ==============================================

// ==============================================
// LikeController (English)
// Handles liking and unliking posts, and fetching like counts.
// ==============================================

// ==============================================
// کنترلر لایک‌ها (فارسی)
// مدیریت لایک و آن‌لایک پست‌ها و دریافت تعداد لایک‌ها
// ==============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';

class LikeController {

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

    // EN: Like a post
    // FA: لایک یک پست
    public function likePost($userId, $postId) {
        $stmt = $this->conn->prepare("SELECT id FROM likes WHERE user_id=:user_id AND post_id=:post_id");
        $stmt->execute([':user_id'=>$userId, ':post_id'=>$postId]);
        if ($stmt->fetch()) {
            echo json_encode(['status'=>'error','message'=>'Already liked']);
            return;
        }

        $stmt = $this->conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)");
        $stmt->execute([':user_id'=>$userId, ':post_id'=>$postId]);

        echo json_encode(['status'=>'ok','message'=>'Post liked']);
    }

    // EN: Unlike a post
    // FA: آن‌لایک یک پست
    public function unlikePost($userId, $postId) {
        $stmt = $this->conn->prepare("DELETE FROM likes WHERE user_id=:user_id AND post_id=:post_id");
        $stmt->execute([':user_id'=>$userId, ':post_id'=>$postId]);

        echo json_encode(['status'=>'ok','message'=>'Post unliked']);
    }

    // EN: Get like count for a post
    // FA: دریافت تعداد لایک‌ها برای یک پست
    public function getLikes($postId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM likes WHERE post_id=:post_id");
        $stmt->execute([':post_id'=>$postId]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

        echo json_encode(['status'=>'ok','likes'=>$count]);
    }
}