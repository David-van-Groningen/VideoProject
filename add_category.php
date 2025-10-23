<?php

require 'config.php';
$user = current_user($pdo);
if (!$user || !$user['is_admin']) {
    http_response_code(403);
    echo "Alleen admins mogen categorieën aanmaken.";
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $color = trim($_POST['color'] ?? '#a2f5a2');
    $image = trim($_POST['image_url'] ?? '');

    if ($name === '') $errors[] = "Naam is verplicht";
    if ($slug === '') $errors[] = "Slug is verplicht";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, image_url, color_hex) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $image, $color]);
        header('Location: index.php');
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Nieuwe categorie</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg">
<main class="container glass-card">
<h1>Nieuwe categorie toevoegen</h1>
<?php foreach ($errors as $e): ?>
<div class="notice err"><?=htmlspecialchars($e)?></div>
<?php endforeach; ?>
<form method="post" class="form-fancy">
    <label>Naam <input name="name" required></label>
    <label>Slug <input name="slug" required></label>
    <label>Kleur <input type="color" name="color" value="#a2f5a2"></label>
    <label>Afbeelding URL <input name="image_url"></label>
    <div class="actions">
        <button class="btn btn-purple">Voeg toe</button>
        <a class="btn btn-green ghost" href="index.php">Annuleer</a>
    </div>
</form>
</main>
</body>
</html>
