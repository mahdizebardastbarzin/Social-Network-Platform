<?php
// ==============================================
// File: backend/controllers/FollowController.php
// ==============================================

// ==============================================
// FollowController (English)
// Handles following/unfollowing users and retrieving follow lists.
// ==============================================

// ==============================================
// کنترلر فالو (فارسی)
// مدیریت دنبال کردن/دنبال نکردن کاربران و دریافت لیست فالوورها
// ==============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';

class FollowController {

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

    // EN: Follow a user
    // FA: دنبال کردن یک کاربر
    public function followUser($followerId, $followingId) {
        if ($followerId == $followingId) {
            echo json_encode(['status'=>'error','message'=>'Cannot follow yourself']);
            return;
        }

        $stmt = $this->conn->prepare("SELECT id FROM follows WHERE follower_id=:follower AND following_id=:following");
        $stmt->execute([':follower'=>$followerId, ':following'=>$followingId]);
        if ($stmt->fetch()) {
            echo json_encode(['status'=>'error','message'=>'Already following']);
            return;
        }

        $stmt = $this->conn->prepare("INSERT INTO follows (follower_id, following_id) VALUES (:follower, :following)");
        $stmt->execute([':follower'=>$followerId, ':following'=>$followingId]);

        echo json_encode(['status'=>'ok','message'=>'User followed']);
    }

    // EN: Unfollow a user
    // FA: دنبال نکردن یک کاربر
    public function unfollowUser($followerId, $followingId) {
        $stmt = $this->conn->prepare("DELETE FROM follows WHERE follower_id=:follower AND following_id=:following");
        $stmt->execute([':follower'=>$followerId, ':following'=>$followingId]);

        echo json_encode(['status'=>'ok','message'=>'User unfollowed']);
    }

    // EN: Get followers of a user
    // FA: دریافت فالوورهای یک کاربر
    public function getFollowers($userId) {
        $stmt = $this->conn->prepare("SELECT u.id, u.username, u.profile_image 
                                      FROM follows f 
                                      JOIN users u ON f.follower_id = u.id 
                                      WHERE f.following_id=:user_id");
        $stmt->execute([':user_id'=>$userId]);
        $followers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status'=>'ok','followers'=>$followers]);
    }

    // EN: Get following list of a user
    // FA: دریافت لیست افرادی که یک کاربر دنبال می‌کند
    public function getFollowing($userId) {
        $stmt = $this->conn->prepare("SELECT u.id, u.username, u.profile_image 
                                      FROM follows f 
                                      JOIN users u ON f.following_id = u.id 
                                      WHERE f.follower_id=:user_id");
        $stmt->execute([':user_id'=>$userId]);
        $following = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status'=>'ok','following'=>$following]);
    }
}