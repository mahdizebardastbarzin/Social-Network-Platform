<?php
// ==============================================
// File: backend/models/Admin.php
// ==============================================

// ==============================================
// Admin model (English)
// Handles administrative database operations
// ==============================================

// ==============================================
// مدل مدیریت (فارسی)
// مدیریت عملیات دیتابیس مرتبط با مدیریت کل سیستم
// ==============================================

require_once __DIR__ . '/../config/database.php';

class Admin {

    private $conn;
    private $table_name = 'users'; // Admins are users with is_admin flag

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // EN: Get all users
    // FA: دریافت تمام کاربران
    public function getAllUsers() {
        $stmt = $this->conn->prepare("SELECT id, username, email, created_at FROM {$this->table_name}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // EN: Delete a user
    // FA: حذف یک کاربر
    public function deleteUser($user_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE id=:id");
        return $stmt->execute([':id'=>$user_id]);
    }

    // EN: Promote user to admin (set is_admin flag)
    // FA: ارتقاء کاربر به مدیر (تنظیم پرچم is_admin)
    public function promoteToAdmin($user_id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table_name} SET is_admin=1 WHERE id=:id");
        return $stmt->execute([':id'=>$user_id]);
    }

    // EN: Demote admin to regular user
    // FA: کاهش سطح مدیر به کاربر عادی
    public function demoteAdmin($user_id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table_name} SET is_admin=0 WHERE id=:id");
        return $stmt->execute([':id'=>$user_id]);
    }
}