<?php
require_once __DIR__ . '/../inc/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if (!$username || !$password) {
    $error = 'Username and password are required.';
  } else {
    $stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password'])) {
      $error = 'Invalid credentials.';
    } else {
      login_user($user);
      if ($user['role'] === 'admin') {
        header('Location: /myprojects/admin/');
      } else {
        header('Location: /myprojects/');
      }
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
  <title>Login - Ezprezone</title>
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
      <h1>Login</h1>
      <?php if (!empty($error)) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
      <form method="post" action="" class="login-form">
        <label for="username">Username or email</label>
        <input id="username" name="username" type="text" autocomplete="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" />

        <label for="password">Password</label>
        <div class="row">
          <input id="password" name="password" type="password" autocomplete="current-password" required />
          <button type="button" class="toggle-password" data-target="password">Show</button>
        </div>

        <label for="password">Password</label>
        <div class="row">
          <input id="password" name="password" type="password" required />
          <button type="button" class="toggle-password" data-target="password">Show</button>
        </div>

        <div class="actions">
          <div class="small"><a href="#">Forgot password?</a></div>
          <button class="button" type="submit">Login</button>
        </div>
      </form>
      <p class="small">No account? <a href="/myprojects/auth/signup.php">Sign up</a></p>
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