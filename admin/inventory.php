<?php
require_once __DIR__ . '/../inc/auth.php';
require_login(); require_admin();

// Add or update product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $sku = trim($_POST['sku'] ?? null) ?: null;
  $price = (float)($_POST['price'] ?? 0);
  $stock = (int)($_POST['stock'] ?? 0);
  if ($name) {
    $stmt = $pdo->prepare('INSERT INTO products (sku, name, price, stock) VALUES (?, ?, ?, ?)');
    $stmt->execute([$sku, $name, $price, $stock]);
  }
  header('Location: /myprojects/admin/inventory.php'); exit;
}

$stmt = $pdo->query('SELECT id, sku, name, price, stock FROM products ORDER BY created_at DESC');
$products = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Inventory</title></head>
<body>
  <h1>Inventory</h1>
  <form method="post">
    <label>SKU <input name="sku"></label><br>
    <label>Name <input name="name" required></label><br>
    <label>Price <input name="price" type="number" step="0.01" value="0.00"></label><br>
    <label>Stock <input name="stock" type="number" value="0"></label><br>
    <button type="submit">Add product</button>
  </form>

  <table border="1">
    <thead><tr><th>ID</th><th>SKU</th><th>Name</th><th>Price</th><th>Stock</th></tr></thead>
    <tbody>
      <?php foreach($products as $p): ?>
      <tr>
        <td><?php echo $p['id']; ?></td>
        <td><?php echo htmlspecialchars($p['sku']); ?></td>
        <td><?php echo htmlspecialchars($p['name']); ?></td>
        <td><?php echo $p['price']; ?></td>
        <td><?php echo $p['stock']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p><a href="/myprojects/admin/">Back</a></p>
</body>
</html>