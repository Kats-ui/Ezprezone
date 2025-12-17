<?php
require_once __DIR__ . '/../inc/auth.php';
require_login(); require_admin();

$stmt = $pdo->query('SELECT lp.id, lp.user_id, lp.points, lp.last_updated, u.username FROM loyalty_progress lp JOIN users u ON lp.user_id = u.id ORDER BY lp.points DESC');
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Loyalty Progress</title></head>
<body>
  <h1>Loyalty Progress</h1>
  <table border="1">
    <thead><tr><th>User</th><th>Points</th><th>Updated</th></tr></thead>
    <tbody>
      <?php foreach($rows as $r): ?>
      <tr>
        <td><?php echo htmlspecialchars($r['username']); ?></td>
        <td><?php echo $r['points']; ?></td>
        <td><?php echo $r['last_updated']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p><a href="/myprojects/admin/">Back</a></p>
</body>
</html>