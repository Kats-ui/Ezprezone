<?php
require_once __DIR__ . '/inc/db.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if (!$name || ($email && !filter_var($email, FILTER_VALIDATE_EMAIL))) { header('Location: /myprojects/loyaltycard.html?status=error'); exit; }

$stmt = $pdo->prepare("INSERT INTO loyalty_signups (name,email,phone) VALUES (?, ?, ?)");
$stmt->execute([$name, $email ?: null, $phone ?: null]);

header('Location: /myprojects/loyaltycard.html?status=ok');
exit;