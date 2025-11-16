<?php
// ==============================================
// File: backend/models/Group.php
// ==============================================

// ==============================================
// Group model (English)
// Handles group-related database operations
// ==============================================

// ==============================================
// مدل گروه (فارسی)
// مدیریت عملیات دیتابیس مرتبط با گروه‌ها
// ==============================================

require_once __DIR__ . '/../config/database.php';

class Group {

    private $conn;
    private $table_name = 'groups';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // EN: Create a new group
    // FA: ایجاد یک گروه جدید
    public function create($name, $description = null) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table_name} (name, description) VALUES (:name, :description)");
        return $stmt->execute([':name'=>$name, ':description'=>$description]);
    }

    // EN: Get group by ID
    // FA: دریافت گروه بر اساس شناسه
    public function getById($group_id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE id=:id");
        $stmt->execute([':id'=>$group_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // EN: Get all groups
    // FA: دریافت تمام گروه‌ها
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // EN: Delete a group
    // FA: حذف یک گروه
    public function delete($group_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE id=:id");
        return $stmt->execute([':id'=>$group_id]);
    }
}