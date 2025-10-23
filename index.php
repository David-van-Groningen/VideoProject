<?php

require 'config.php';
if (!is_logged_in()) header('Location: login.php');

$stmt = $pdo->query("SELECT id,name,slug,image_url,color_hex FROM categories ORDER BY name");
$cats = $stmt->fetchAll();
$user = current_user($pdo);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Home - Categorieën</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg">
<header class="topbar">
    <div class="left">
        <a class="btn btn-green" href="index.php">Home</a>
    </div>
    <div class="right">
        <span>Welkom <?=htmlspecialchars($user['display_name']?:$user['username'])?></span>
        <a class="btn btn-purple ghost" href="logout.php">Logout</a>
    </div>
</header>

<main class="container">
    <h2>Categorieën</h2>
    <div class="grid cards">
        <?php foreach ($cats as $c): ?>
        <article class="card-cat glass-panel" style="--accent:<?=htmlspecialchars($c['color_hex'])?>">
            <a href="category.php?slug=<?=urlencode($c['slug'])?>">
                <div class="img" style="background-image:url('<?=htmlspecialchars($c['image_url'] ?: 'assets/default_cat.jpg')?>')"></div>
                <h3><?=htmlspecialchars($c['name'])?></h3>
            </a>
        </article>
        <?php endforeach; ?>
    </div>
</main>

<script src="assets/app.js"></script>
</body>
</html>
