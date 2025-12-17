<?php
require_once __DIR__ . '/../inc/auth.php';
require_login();
require_admin();
// Simple POS: list products and allow creating a transaction
$stmt = $pdo->query('SELECT id, sku, name, price, stock FROM products');
$products = $stmt->fetchAll();

// Handle simple transaction create
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $items = $_POST['items'] ?? [];
  // items expected as array of ['product_id' => qty]
  $total = 0;
  foreach ($items as $productId => $qty) {
    $qty = (int)$qty;
    if ($qty <= 0) continue;
    $pstmt = $pdo->prepare('SELECT price, stock FROM products WHERE id = ?');
    $pstmt->execute([$productId]);
    $p = $pstmt->fetch();
    if (!$p) continue;
    if ($p['stock'] < $qty) { $error = 'Insufficient stock for product '.$productId; break; }
    $total += $p['price'] * $qty;
  }
  if (empty($error)) {
    $pdo->beginTransaction();
    $tstmt = $pdo->prepare('INSERT INTO transactions (user_id, total) VALUES (?, ?)');
    $tstmt->execute([null, $total]);
    $txnId = $pdo->lastInsertId();
    foreach ($items as $productId => $qty) {
      $qty = (int)$qty; if ($qty <= 0) continue;
      $pstmt = $pdo->prepare('SELECT price FROM products WHERE id = ?'); $pstmt->execute([$productId]); $p = $pstmt->fetch();
      $ipstmt = $pdo->prepare('INSERT INTO transaction_items (transaction_id, product_id, qty, price) VALUES (?, ?, ?, ?)');
      $ipstmt->execute([$txnId, $productId, $qty, $p['price']]);
      $upd = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
      $upd->execute([$qty, $productId]);
    }
    $pdo->commit();
    header('Location: /myprojects/admin/transactions.php'); exit;
  }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>POS</title></head>
<body>
  <h1>Point of Sale</h1>
  <?php if(!empty($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
  <form method="post">
    <table border="1">
      <thead><tr><th>Product</th><th>Price</th><th>Stock</th><th>Qty</th></tr></thead>
      <tbody>
        <?php foreach($products as $p): ?>
        <tr>
          <td><?php echo htmlspecialchars($p['name']); ?></td>
          <td><?php echo htmlspecialchars($p['price']); ?></td>
          <td><?php echo htmlspecialchars($p['stock']); ?></td>
          <td><input type="number" name="items[<?php echo $p['id']; ?>]" min="0" max="<?php echo $p['stock']; ?>" value="0"></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <button type="submit">Create Transaction</button>
  </form>
  <p><a href="/myprojects/admin/">Back</a></p>
</body>
</html>