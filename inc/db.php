<?php
// inc/db.php
$host = '127.0.0.1';
$db   = 'ezprezone';
$user = 'root';
$pass = ''; // default XAMPP root password is empty
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  // Log the error and show a helpful message when run from CLI so scripts can debug
  if (php_sapi_name() === 'cli') {
    // Show full message on CLI for debugging
    fwrite(STDERR, "DB connection error: " . $e->getMessage() . PHP_EOL);
  } else {
    // In web context, log it to the server error log (avoid exposing details to users)
    error_log($e->getMessage());
    http_response_code(500);
    echo "Database connection failed.";
  }
  exit;
}
