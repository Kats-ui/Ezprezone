<?php
// inc/auth.php - helper functions for authentication
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function login_user(array $user): void {
  session_regenerate_id(true);
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['role'] = $user['role'];
}

function logout_user(): void {
  $_SESSION = [];
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
      $params['path'], $params['domain'], $params['secure'], $params['httponly']
    );
  }
  session_destroy();
}

function current_user_id() {
  return $_SESSION['user_id'] ?? null;
}

function current_user() {
  if (!current_user_id()) return null;
  global $pdo;
  $stmt = $pdo->prepare('SELECT id, username, email, role FROM users WHERE id = ?');
  $stmt->execute([current_user_id()]);
  return $stmt->fetch();
}

function is_admin(): bool {
  return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
}

function require_login(): void {
  if (!current_user_id()) {
    header('Location: /myprojects/auth/login.php');
    exit;
  }
}

function require_admin(): void {
  if (!is_admin()) {
    http_response_code(403);
    echo "Forbidden";
    exit;
  }
}
