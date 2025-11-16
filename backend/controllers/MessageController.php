<?php
// ==============================================
// File: backend/controllers/MessageController.php
// ==============================================

// ==============================================
// MessageController (English)
// Handles sending, receiving, and listing messages between users.
// ==============================================

// ==============================================
// کنترلر پیام‌ها (فارسی)
// مدیریت ارسال، دریافت و لیست پیام‌ها بین کاربران
// ==============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';

class MessageController {

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

    // EN: Send a message from one user to another
    // FA: ارسال پیام از یک کاربر به کاربر دیگر
    public function sendMessage($senderId, $receiverId, $message) {
        $stmt = $this->conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender, :receiver, :message)");
        $stmt->execute([':sender'=>$senderId, ':receiver'=>$receiverId, ':message'=>$message]);

        echo json_encode(['status'=>'ok','message'=>'Message sent']);
    }

    // EN: Get all messages between two users
    // FA: دریافت تمام پیام‌ها بین دو کاربر
    public function getMessages($userId1, $userId2) {
        $stmt = $this->conn->prepare("SELECT * FROM messages 
                                      WHERE (sender_id=:user1 AND receiver_id=:user2) 
                                         OR (sender_id=:user2 AND receiver_id=:user1) 
                                      ORDER BY created_at ASC");
        $stmt->execute([':user1'=>$userId1, ':user2'=>$userId2]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status'=>'ok','messages'=>$messages]);
    }

    // EN: Get latest conversations for a user
    // FA: دریافت آخرین مکالمات برای یک کاربر
    public function getConversations($userId) {
        $stmt = $this->conn->prepare("SELECT m.*, u.username, u.profile_image 
                                      FROM messages m 
                                      JOIN users u ON (u.id = IF(m.sender_id=:uid, m.receiver_id, m.sender_id)) 
                                      WHERE m.sender_id=:uid OR m.receiver_id=:uid 
                                      ORDER BY m.created_at DESC");
        $stmt->execute([':uid'=>$userId]);
        $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status'=>'ok','conversations'=>$conversations]);
    }
}