<?php
// backend/config/database.php
// EN: PDO database connection factory.
// FA: کارخانهٔ اتصال PDO به دیتابیس.


class Database {
private $host = 'localhost';
private $db_name = 'social_network';
private $username = 'root';
private $password = '';
public $conn;


// EN: Get DB connection using PDO.
// FA: دریافت اتصال به دیتابیس با PDO.
public function getConnection() {
$this->conn = null;


try {
$this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4", $this->username, $this->password);
$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
// EN: If database not found, return null (caller may call create_db.php first).
// FA: در صورت نبودن دیتابیس، null برگردانید (فایل create_db.php باید ابتدا اجرا شود).
return null;
}


return $this->conn;
}
}