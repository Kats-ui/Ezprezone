<?php
require_once __DIR__ . '/../inc/auth.php';
require_login(); require_admin();
$stmt = $pdo->query('SELECT t.id, t.total, t.created_at, u.username FROM transactions t LEFT JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC LIMIT 100');
$txns = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Transactions</title></head>
<body>
  <h1>Transactions</h1>
  <table border="1">
    <thead><tr><th>ID</th><th>User</th><th>Total</th><th>Created</th></tr></thead>
    <tbody>
      <?php foreach($txns as $t): ?>
        <tr>
          <td><?php echo $t['id']; ?></td>
          <td><?php echo htmlspecialchars($t['username'] ?? 'Guest'); ?></td>
          <td><?php echo $t['total']; ?></td>
          <td><?php echo $t['created_at']; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p><a href="/myprojects/admin/">Back</a></p>
</body>
</html>