<?php

require 'config.php';
if (!is_logged_in()) header('Location: login.php');

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
$stmt->execute([$slug]);
$cat = $stmt->fetch();
if (!$cat) { http_response_code(404); echo "Categorie niet gevonden"; exit; }

$stmt2 = $pdo->prepare("SELECT v.*, u.display_name FROM videos v LEFT JOIN users u ON v.created_by = u.id WHERE category_id=? ORDER BY v.created_at DESC");
$stmt2->execute([$cat['id']]);
$videos = $stmt2->fetchAll();
$user = current_user($pdo);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=htmlspecialchars($cat['name'])?> - Videos</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg">

<header class="topbar">
  <div class="left">
    <a class="btn" href="index.php">Home</a>
  </div>
  <div class="right">
    <span><?=htmlspecialchars($cat['name'])?></span>
    <a class="btn ghost" href="logout.php">Logout</a>
  </div>
</header>

<main class="container">
  <section class="carousel" id="carousel">
    <div class="slides">
      <div class="slide" style="background-image:url('assets/banner1.jpg')"></div>
      <div class="slide" style="background-image:url('assets/banner2.jpg')"></div>
      <div class="slide" style="background-image:url('assets/banner3.jpg')"></div>
    </div>
    <button class="carousel-prev">‹</button>
    <button class="carousel-next">›</button>
  </section>

  <section class="controls" style="display:flex;justify-content:space-between;align-items:center">
    <h2><?=htmlspecialchars($cat['name'])?></h2>
    <div>
      <button class="btn btn-green" id="btn-add-video">Voeg video toe</button>
    </div>
  </section>

  <div class="grid blocks" id="video-grid">
    <?php foreach($videos as $v):
      $title = $v['title'];
      $url = $v['youtube_url'];
      $thumb = $v['thumbnail_url'] ?: ("https://img.youtube.com/vi/" . (preg_match('~(?:v=|youtu\.be/|/embed/)([A-Za-z0-9_-]{6,})~',$url,$m)?$m[1]:'') . "/hqdefault.jpg");
    ?>
    <article class="video-block" data-id="<?= $v['id'] ?>" style="border-color:<?=htmlspecialchars($cat['color_hex'])?>">
      <div class="thumb" style="background-image:url('<?=htmlspecialchars($thumb)?>')"></div>
      <div class="meta">
        <h4><?=htmlspecialchars($title)?></h4>
        <small>toegevoegd door <?=htmlspecialchars($v['display_name'] ?? 'anoniem')?></small>
        <div class="actions">
          <button class="btn play-btn" data-url="<?=htmlspecialchars($url)?>">Open video</button>
          <button class="btn ghost copy-btn" data-url="<?=htmlspecialchars($url)?>">Kopieer link</button>
          <?php if($user['is_admin'] || $v['created_by']==$user['id']): ?>
            <button class="btn btn-purple edit-btn">Edit</button>
            <button class="btn btn-red delete-btn">Delete</button>
          <?php endif; ?>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
</main>

<div class="modal hidden" id="modal">
  <div class="modal-content glass-card">
    <h2 id="modal-title"></h2>
    <form id="modal-form">
      <input type="hidden" name="video_id" value="">
      <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
      <label>Titel <input type="text" name="title" required></label>
      <label>YouTube URL <input type="url" name="youtube_url" required placeholder="https://www.youtube.com/watch?v=..."></label>
      <label>Thumbnail URL (optioneel) <input type="url" name="thumbnail_url"></label>
      <div class="actions" style="margin-top:0.5rem">
        <button type="submit" class="btn btn-green">Opslaan</button>
        <button type="button" class="btn ghost" id="modal-close">Annuleer</button>
      </div>
    </form>
  </div>
</div>

<script src="assets/app.js"></script>
<script>
initCarousel('carousel');
</script>

</body>
</html>
