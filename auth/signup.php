<?php
require_once __DIR__ . '/../inc/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '') ?: null;
  $password = $_POST['password'] ?? '';

  if (!$username || !$password) {
    $error = 'Username and password are required.';
  } else {
    // Check existing
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
      $error = 'Username or email already in use.';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
      $stmt->execute([$username, $email, $hash]);
      $userId = $pdo->lastInsertId();
      // Auto-login
      $stmt = $pdo->prepare('SELECT id, username, role FROM users WHERE id = ?');
      $stmt->execute([$userId]);
      $user = $stmt->fetch();
      login_user($user);
      header('Location: /myprojects/');
      exit;
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign up - Ezprezone</title>
  <link rel="stylesheet" href="/myprojects/Ezprezone/CSS/common.css" />
</head>
<body class="page-transition preload">
  <header class="navbar" role="banner">
    <a href="/myprojects/Ezprezone/index.html" class="logo-link">
      <img class="logo" src="/myprojects/Ezprezone/images/B5/ezprezone-logo.jpg" alt="EZPREZONE Logo" />
    </a>

    <nav role="navigation" aria-label="Main navigation">
      <ul class="nav-links">
        <li><a href="/myprojects/Ezprezone/index.html">Home</a></li>
        <li><a href="/myprojects/Ezprezone/aboutus.html">About</a></li>
        <li><a href="/myprojects/Ezprezone/menu.html">Menu</a></li>
        <li><a href="/myprojects/Ezprezone/loyaltycard.html">Loyalty</a></li>
        <li><a href="/myprojects/Ezprezone/contacts.html">Contact</a></li>
        <li id="authLinks"><a href="/myprojects/auth/login.php">Login</a> | <a href="/myprojects/auth/signup.php">Sign up</a></li>
      </ul>
    </nav>
  </header>

  <main class="auth-page">
    <div class="auth-box">
      <h1>Sign up</h1>
      <?php if (!empty($error)) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
      <form method="post" action="">
        <label for="username">Username</label>
        <input id="username" name="username" type="text" autocomplete="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" />

        <label for="email">Email</label>
        <input id="email" name="email" type="email" autocomplete="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" />

        <label for="password">Password</label>
        <div class="row">
          <input id="password" name="password" type="password" autocomplete="new-password" required />
          <button type="button" class="toggle-password" data-target="password">Show</button>
        </div>

        <div class="actions">
          <div class="small">Password must be at least 6 characters</div>
          <button class="button" type="submit">Sign up</button>
        </div>
      </form>
      <p class="small">Already have an account? <a href="/myprojects/auth/login.php">Login</a></p>
    </div>
  </main>

  <footer class="footer"><p>© 2025 Ezprezo. All Rights Reserved.</p></footer>
  <script src="/myprojects/Ezprezone/js/auth_status.js"></script>
  <script src="/myprojects/Ezprezone/js/auth_form.js"></script>
  <script>
    (function(){
      const body = document.body;
      if(!body) return;
      body.classList.add('page-transition');
      window.addEventListener('DOMContentLoaded', ()=> requestAnimationFrame(()=> body.classList.remove('preload')));
    })();
  </script>
</body>
</html>