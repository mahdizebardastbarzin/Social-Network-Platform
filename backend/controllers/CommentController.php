<?php
// ==============================================
// File: backend/controllers/CommentController.php
// ==============================================

// ==============================================
// CommentController (English)
// Handles creating, updating, deleting, and fetching comments.
// ==============================================

// ==============================================
// کنترلر کامنت‌ها (فارسی)
// مدیریت ایجاد، بروزرسانی، حذف و دریافت کامنت‌ها
// ==============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';

class CommentController {

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

    // EN: Fetch comments for a specific post
    // FA: دریافت کامنت‌ها برای یک پست مشخص
    public function getComments($postId) {
        $stmt = $this->conn->prepare("SELECT c.id, c.user_id, u.username, u.profile_image, c.comment, c.created_at 
                                      FROM comments c 
                                      JOIN users u ON c.user_id = u.id 
                                      WHERE c.post_id=:post_id 
                                      ORDER BY c.created_at ASC");
        $stmt->execute([':post_id'=>$postId]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status'=>'ok','comments'=>$comments]);
    }

    // EN: Create a new comment
    // FA: ایجاد یک کامنت جدید
    public function createComment($userId, $postId, $commentText) {
        if (!$commentText) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'No comment provided']);
            return;
        }

        $stmt = $this->conn->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (:user_id, :post_id, :comment)");
        $stmt->execute([':user_id'=>$userId, ':post_id'=>$postId, ':comment'=>$commentText]);

        echo json_encode(['status'=>'ok','message'=>'Comment created','comment_id'=>$this->conn->lastInsertId()]);
    }

    // EN: Delete a comment
    // FA: حذف یک کامنت
    public function deleteComment($commentId, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM comments WHERE id=:id AND user_id=:user_id");
        $stmt->execute([':id'=>$commentId, ':user_id'=>$userId]);

        echo json_encode(['status'=>'ok','message'=>'Comment deleted']);
    }

    // EN: Update a comment
    // FA: بروزرسانی یک کامنت
    public function updateComment($commentId, $userId, $commentText) {
        if (!$commentText) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'No comment content provided']);
            return;
        }

        $stmt = $this->conn->prepare("UPDATE comments SET comment=:comment WHERE id=:id AND user_id=:user_id");
        $stmt->execute([':comment'=>$commentText, ':id'=>$commentId, ':user_id'=>$userId]);

        echo json_encode(['status'=>'ok','message'=>'Comment updated']);
    }
}