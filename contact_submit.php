<?php
require_once __DIR__ . '/inc/db.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$message || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('Location: /myprojects/contacts.html?status=error');
  exit;
}

$stmt = $pdo->prepare("INSERT INTO contacts (name,email,message) VALUES (?, ?, ?)");
$stmt->execute([$name, $email, $message]);

header('Location: /myprojects/contacts.html?status=ok');
exit;