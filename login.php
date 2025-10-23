<?php

require 'config.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else {
        $err = "Onjuiste inloggegevens";
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg">
  <main class="container glass-card">
    <h1>Login</h1>
    <?php if($err): ?>
      <div class="notice err"><?=htmlspecialchars($err)?></div>
    <?php endif; ?>
    <form method="post" class="form-fancy">
      <label>Username <input name="username" required></label>
      <label>Password <input type="password" name="password" required></label>
      <div class="actions">
        <button class="btn btn-purple">Login</button>
        <a class="btn btn-green ghost" href="register.php">Register</a>
      </div>
    </form>
  </main>
</body>
</html>
