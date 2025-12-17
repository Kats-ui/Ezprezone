<?php
require_once __DIR__ . '/../inc/auth.php';
require_login();
require_admin();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin Dashboard</title></head>
<body>
  <h1>Admin Dashboard</h1>
  <p>Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></p>
  <ul>
    <li><a href="/myprojects/admin/pos.php">Point of Sale</a></li>
    <li><a href="/myprojects/admin/inventory.php">Inventory</a></li>
    <li><a href="/myprojects/admin/transactions.php">Transactions</a></li>
    <li><a href="/myprojects/admin/loyalty.php">Loyalty Progress</a></li>
    <li><a href="/myprojects/auth/logout.php">Logout</a></li>
  </ul>
</body>
</html>