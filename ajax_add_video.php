<?php

require 'config.php';
header('Content-Type: application/json');

if (!is_logged_in()) {
    http_response_code(403);
    echo json_encode(['status'=>'error','error'=>'Niet ingelogd']);
    exit;
}

$cat_id = intval($_POST['category_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$youtube_url = trim($_POST['youtube_url'] ?? '');
$thumbnail_url = trim($_POST['thumbnail_url'] ?? '');

if ($title === '' || $youtube_url === '') {
    http_response_code(400);
    echo json_encode(['status'=>'error','error'=>'Titel en YouTube URL zijn verplicht']);
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM categories WHERE id=?");
$stmt->execute([$cat_id]);
if (!$stmt->fetch()) {
    http_response_code(404);
    echo json_encode(['status'=>'error','error'=>'Categorie niet gevonden']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO videos (category_id, title, youtube_url, thumbnail_url, created_by) VALUES (?,?,?,?,?)");
$stmt->execute([$cat_id,$title,$youtube_url,$thumbnail_url ?: null,$_SESSION['user_id']]);

echo json_encode(['status'=>'success']);
