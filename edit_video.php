<?php

require 'config.php';
$user = current_user($pdo);
$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM videos WHERE id=?");
$stmt->execute([$id]);
$video = $stmt->fetch();
if (!$video) { die("Video niet gevonden"); }

if (!$user || (!$user['is_admin'] && $video['created_by'] != $user['id'])) {
    http_response_code(403);
    die("Niet toegestaan");
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $youtube = trim($_POST['youtube_url'] ?? '');
    $thumb = trim($_POST['thumbnail_url'] ?? '');
    if ($title === '') $errors[] = "Titel verplicht";
    if ($youtube === '') $errors[] = "YouTube URL verplicht";

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE videos SET title=?, youtube_url=?, thumbnail_url=? WHERE id=?");
        $stmt->execute([$title, $youtube, $thumb ?: null, $id]);
        header('Location: category.php?slug=' . urlencode($_POST['slug']));
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Video</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg">
<main class="container glass-card">
<h1>Edit Video</h1>
<?php foreach ($errors as $e): ?>
<div class="notice err"><?=htmlspecialchars($e)?></div>
<?php endforeach; ?>
<form method="post" class="form-fancy">
    <label>Titel <input name="title" required value="<?=htmlspecialchars($video['title'])?>"></label>
    <label>YouTube URL <input name="youtube_url" required value="<?=htmlspecialchars($video['youtube_url'])?>"></label>
    <label>Thumbnail URL (optioneel) <input name="thumbnail_url" value="<?=htmlspecialchars($video['thumbnail_url'])?>"></label>
    <input type="hidden" name="slug" value="<?=htmlspecialchars($_GET['slug'] ?? '')?>">
    <div class="actions">
        <button class="btn btn-purple">Opslaan</button>
        <a class="btn btn-green ghost" href="category.php?slug=<?=urlencode($_GET['slug'] ?? '')?>">Annuleer</a>
    </div>
</form>
</main>
</body>
</html>
