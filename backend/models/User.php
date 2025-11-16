<?php
// ==============================================
// File: backend/models/User.php
// ==============================================

// ==============================================
// User model (English)
// Handles user-related database operations
// ==============================================

// ==============================================
// مدل کاربر (فارسی)
// مدیریت عملیات دیتابیس مرتبط با کاربران
// ==============================================

require_once __DIR__ . '/../config/database.php';

class User {

    private $conn;
    private $table_name = 'users';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // EN: Create new user
    // FA: ایجاد کاربر جدید
    public function create($username, $email, $password_hash) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table_name} (username, email, password_hash) VALUES (:username, :email, :password)");
        return $stmt->execute([':username'=>$username, ':email'=>$email, ':password'=>$password_hash]);
    }

    // EN: Get user by email
    // FA: دریافت کاربر بر اساس ایمیل
    public function getByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE email=:email LIMIT 1");
        $stmt->execute([':email'=>$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // EN: Get user by ID
    // FA: دریافت کاربر بر اساس ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE id=:id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // EN: Update user profile
    // FA: به‌روزرسانی پروفایل کاربر
    public function updateProfile($id, $profile_image=null, $cover_image=null, $bio=null, $links=null, $privacy_setting='public') {
        $stmt = $this->conn->prepare("UPDATE {$this->table_name} SET profile_image=:profile_image, cover_image=:cover_image, bio=:bio, links=:links, privacy_setting=:privacy WHERE id=:id");
        return $stmt->execute([
            ':profile_image'=>$profile_image,
            ':cover_image'=>$cover_image,
            ':bio'=>$bio,
            ':links'=>$links,
            ':privacy'=>$privacy_setting,
            ':id'=>$id
        ]);
    }

    // EN: Delete user by ID
    // FA: حذف کاربر بر اساس ID
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE id=:id");
        return $stmt->execute([':id'=>$id]);
    }
}