<?php
// Usage: php scripts/create_admin.php admin_username admin@example.com password
require_once __DIR__ . '/../inc/db.php';

if ($argc < 4) {
    echo "Usage: php create_admin.php <username> <email> <password>\n";
    exit(1);
}

[$prog, $username, $email, $password] = $argv;

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, "admin")');
try {
    $stmt->execute([$username, $email, $hash]);
    echo "Admin user created: $username ($email)\n";
} catch (PDOException $e) {
    echo "Error creating admin: " . $e->getMessage() . "\n";
}
