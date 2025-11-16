<?php
// ==============================================
// File: backend/controllers/PostController.php
// ==============================================

// ==============================================
// PostController (English)
// Handles creating, updating, deleting, and fetching posts.
// ==============================================

// ==============================================
// کنترلر پست‌ها (فارسی)
// مدیریت ایجاد، بروزرسانی، حذف و دریافت پست‌ها
// ==============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';
require_once __DIR__ . '/../helpers/validation_helper.php';

class PostController {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
        if (!$this->conn) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database connection failed. Run create_db.php first.']);
            exit;
        }
    }

    // EN: Fetch all posts with optional user filter
    // FA: دریافت همه پست‌ها با فیلتر اختیاری کاربر
    public function getPosts($userId = null) {
        $sql = "SELECT p.id, p.user_id, u.username, u.profile_image, p.content, p.media_url, p.type, p.created_at 
                FROM posts p 
                JOIN users u ON p.user_id = u.id";

        $params = [];
        if ($userId) {
            $sql .= " WHERE p.user_id=:user_id";
            $params[':user_id'] = $userId;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status'=>'ok','posts'=>$posts]);
    }

    // EN: Create a new post
    // FA: ایجاد یک پست جدید
    public function createPost($userId, $data) {
        if (!isset($data['content']) && !isset($data['media_url'])) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'No content provided']);
            return;
        }

        $stmt = $this->conn->prepare("INSERT INTO posts (user_id, content, media_url, type) VALUES (:user_id, :content, :media_url, :type)");
        $stmt->execute([
            ':user_id' => $userId,
            ':content' => $data['content'] ?? null,
            ':media_url' => $data['media_url'] ?? null,
            ':type' => $data['type'] ?? 'text'
        ]);

        echo json_encode(['status'=>'ok','message'=>'Post created','post_id'=>$this->conn->lastInsertId()]);
    }

    // EN: Delete a post by ID
    // FA: حذف پست بر اساس شناسه
    public function deletePost($postId, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM posts WHERE id=:id AND user_id=:user_id");
        $stmt->execute([':id'=>$postId, ':user_id'=>$userId]);

        echo json_encode(['status'=>'ok','message'=>'Post deleted']);
    }

    // EN: Update a post
    // FA: بروزرسانی پست
    public function updatePost($postId, $userId, $data) {
        $fields = [];
        $params = [':id'=>$postId, ':user_id'=>$userId];

        if (isset($data['content'])) {
            $fields[] = 'content=:content';
            $params[':content'] = $data['content'];
        }
        if (isset($data['media_url'])) {
            $fields[] = 'media_url=:media_url';
            $params[':media_url'] = $data['media_url'];
        }
        if (empty($fields)) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'No fields to update']);
            return;
        }

        $sql = "UPDATE posts SET " . implode(',', $fields) . " WHERE id=:id AND user_id=:user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        echo json_encode(['status'=>'ok','message'=>'Post updated']);
    }
}