<?php

require 'config.php';
if (!is_logged_in()) header('Location: login.php');

$cat_id = intval($_GET['category'] ?? 0);
$stmt = $pdo->prepare("SELECT id,name FROM categories WHERE id = ?");
$stmt->execute([$cat_id]);
$cat = $stmt->fetch();
if (!$cat) { echo "Categorie niet gevonden."; exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $youtube_url = trim($_POST['youtube_url'] ?? '');
    $thumb = trim($_POST['thumbnail_url'] ?? '');
    if ($title === '') $errors[] = "Titel verplicht";
    if ($youtube_url === '') $errors[] = "Youtube URL verplicht";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO videos (category_id, title, youtube_url, thumbnail_url, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$cat_id, $title, $youtube_url, $thumb ?: null, $_SESSION['user_id']]);
        header('Location: category.php?slug=' . urlencode($cat['slug']));
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Voeg video toe</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg">
  <main class="container card">
    <h1>Voeg video toe aan <?=htmlspecialchars($cat['name'])?></h1>
    <?php foreach ($errors as $e): ?>
      <div class="notice err"><?=htmlspecialchars($e)?></div>
    <?php endforeach; ?>
    <form method="post">
      <label>Titel <input name="title" required></label>
      <label>YouTube URL <input name="youtube_url" required placeholder="https://www.youtube.com/watch?v=..." ></label>
      <label>Thumbnail URL (optioneel) <input name="thumbnail_url"></label>
      <div style="display:flex;gap:.5rem">
        <button class="btn">Toevoegen</button>
        <a class="btn ghost" href="category.php?slug=<?=urlencode($cat['slug'])?>">Annuleer</a>
      </div>
    </form>
  </main>
</body>
</html>
