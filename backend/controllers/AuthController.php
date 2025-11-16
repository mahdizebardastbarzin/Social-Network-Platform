<?php
// ==============================================
// File: backend/controllers/AuthController.php
// ==============================================

// ==============================================
// AuthController (English)
// Handles user registration, login, logout, and token refresh.
// ==============================================

// ==============================================
// کنترلر احراز هویت (فارسی)
// مدیریت ثبت‌نام، ورود، خروج و تجدید توکن کاربران.
// ==============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class AuthController {

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

    // EN: Register new user
    // FA: ثبت‌نام کاربر جدید
    public function register(array $data) {
        // Validation
        if (!ValidationHelper::validateEmail($data['email'] ?? '') ||
            !ValidationHelper::validateUsername($data['username'] ?? '') ||
            !ValidationHelper::validatePassword($data['password'] ?? '')) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }

        // Check existing user
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
        $stmt->execute([':email'=>$data['email'], ':username'=>$data['username']]);
        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode(['status'=>'error', 'message'=>'User already exists']);
            return;
        }

        // Insert user
        $passwordHash = AuthHelper::hashPassword($data['password']);
        $stmt = $this->conn->prepare("INSERT INTO users (username,email,password_hash) VALUES (:username,:email,:password)");
        $stmt->execute([':username'=>$data['username'], ':email'=>$data['email'], ':password'=>$passwordHash]);

        echo json_encode(['status'=>'ok','message'=>'User registered successfully']);
    }

    // EN: Login user
    // FA: ورود کاربر
    public function login(array $data) {
        if (!isset($data['email'],$data['password'])) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'Missing fields']);
            return;
        }

        $stmt = $this->conn->prepare("SELECT id,email,password_hash FROM users WHERE email=:email LIMIT 1");
        $stmt->execute([':email'=>$data['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !AuthHelper::verifyPassword($data['password'],$user['password_hash'])) {
            http_response_code(401);
            echo json_encode(['status'=>'error','message'=>'Invalid credentials']);
            return;
        }

        $token = AuthHelper::generateToken($user);
        echo json_encode(['status'=>'ok','token'=>$token]);
    }

    // EN: Placeholder for logout
    // FA: نگارهٔ اولیه خروج از سیستم
    public function logout() {
        // JWT is stateless; logout handled on client side
        echo json_encode(['status'=>'ok','message'=>'Logout successful']);
    }

    // EN: Placeholder for refresh token
    // FA: نگارهٔ اولیه تجدید توکن
    public function refreshToken() {
        echo json_encode(['status'=>'ok','token'=>'NEW_TOKEN_PLACEHOLDER']);
    }
}