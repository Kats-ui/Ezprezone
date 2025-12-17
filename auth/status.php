<?php
require_once __DIR__ . '/../inc/auth.php';
header('Content-Type: application/json; charset=utf-8');
$user = current_user();
$response = [
  'logged_in' => (bool)$user,
  'username' => $user['username'] ?? null,
  'role' => $user['role'] ?? null,
];
echo json_encode($response);
