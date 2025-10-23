<?php
// register.php
require 'config.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $display = trim($_POST['display_name'] ?? '');

    if (strlen($username) < 3) $errors[] = "Gebruikersnaam >= 3 karakters";
    if (strlen($password) < 6) $errors[] = "Wachtwoord >= 6 karakters";

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, display_name) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $hash, $display]);
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') $errors[] = "Gebruikersnaam bestaat al";
            else $errors[] = "Database fout: " . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg">
  <main class="container card">
    <h1>Register</h1>
    <?php foreach ($errors as $err): ?>
      <div class="notice err"><?=htmlspecialchars($err)?></div>
    <?php endforeach; ?>
    <form method="post">
      <label>Username <input name="username" required></label>
      <label>Display name <input name="display_name"></label>
      <label>Password <input type="password" name="password" required></label>
      <button type="submit" class="btn">Maak account</button>
    </form>
    <p><a href="login.php">Terug naar login</a></p>
  </main>
</body>
</html>
