<?php
$pdo->exec("CREATE TABLE IF NOT EXISTS `likes` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`user_id` INT NOT NULL,
`post_id` INT NOT NULL,
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");


// EN: Create comments table.
// FA: جدول comments را ایجاد می‌کنیم.
$pdo->exec("CREATE TABLE IF NOT EXISTS `comments` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`user_id` INT NOT NULL,
`post_id` INT NOT NULL,
`comment` TEXT NOT NULL,
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");


// EN: Create follows table.
// FA: جدول follows را ایجاد می‌کنیم.
$pdo->exec("CREATE TABLE IF NOT EXISTS `follows` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`follower_id` INT NOT NULL,
`following_id` INT NOT NULL,
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");


// EN: Create messages table.
// FA: جدول messages را ایجاد می‌کنیم.
$pdo->exec("CREATE TABLE IF NOT EXISTS `messages` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`sender_id` INT NOT NULL,
`receiver_id` INT NOT NULL,
`message` TEXT NOT NULL,
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");


// EN: Create groups table.
// FA: جدول groups را ایجاد می‌کنیم.
$pdo->exec("CREATE TABLE IF NOT EXISTS `groups` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`name` VARCHAR(150) NOT NULL,
`description` TEXT NULL,
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");


// EN: Create notifications table.
// FA: جدول notifications را ایجاد می‌کنیم.
$pdo->exec("CREATE TABLE IF NOT EXISTS `notifications` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`user_id` INT NOT NULL,
`type` VARCHAR(50) NOT NULL,
`content` TEXT NULL,
`is_read` TINYINT(1) DEFAULT 0,
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");


echo json_encode(['status' => 'ok', 'message' => 'Database and tables created successfully.']);
exit;


} catch (PDOException $e) {
// EN: Return error in JSON.
// FA: خطا را به‌صورت JSON برمی‌گردانیم.
http_response_code(500);
echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
exit;
}