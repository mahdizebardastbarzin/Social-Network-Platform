<?php
// ==============================================
// File: backend/controllers/GroupController.php
// ==============================================

// ==============================================
// GroupController (English)
// Handles creating groups, adding/removing users, listing groups.
// ==============================================

// ==============================================
// کنترلر گروه‌ها (فارسی)
// مدیریت ایجاد گروه‌ها، افزودن/حذف کاربران و لیست گروه‌ها
// ==============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';

class GroupController {

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

    // EN: Create a new group
    // FA: ایجاد یک گروه جدید
    public function createGroup($name, $description=null) {
        $stmt = $this->conn->prepare("INSERT INTO groups (name, description) VALUES (:name, :desc)");
        $stmt->execute([':name'=>$name, ':desc'=>$description]);

        echo json_encode(['status'=>'ok','message'=>'Group created','group_id'=>$this->conn->lastInsertId()]);
    }

    // EN: List all groups
    // FA: لیست تمام گروه‌ها
    public function listGroups() {
        $stmt = $this->conn->query("SELECT * FROM groups ORDER BY created_at DESC");
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status'=>'ok','groups'=>$groups]);
    }

    // EN: Delete a group
    // FA: حذف یک گروه
    public function deleteGroup($groupId) {
        $stmt = $this->conn->prepare("DELETE FROM groups WHERE id=:id");
        $stmt->execute([':id'=>$groupId]);

        echo json_encode(['status'=>'ok','message'=>'Group deleted']);
    }
}