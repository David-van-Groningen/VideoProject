<?php

require 'config.php';
header('Content-Type: application/json');

if (!is_logged_in()) {
    http_response_code(403);
    echo json_encode(['status'=>'error','error'=>'Niet ingelogd']);
    exit;
}

$video_id = intval($_POST['video_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$youtube_url = trim($_POST['youtube_url'] ?? '');
$thumbnail_url = trim($_POST['thumbnail_url'] ?? '');

if ($title === '' || $youtube_url === '') {
    http_response_code(400);
    echo json_encode(['status'=>'error','error'=>'Titel en YouTube URL zijn verplicht']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM videos WHERE id=?");
$stmt->execute([$video_id]);
$video = $stmt->fetch();
if (!$video) {
    http_response_code(404);
    echo json_encode(['status'=>'error','error'=>'Video niet gevonden']);
    exit;
}

$user = current_user($pdo);
if (!$user['is_admin'] && $video['created_by'] != $user['id']) {
    http_response_code(403);
    echo json_encode(['status'=>'error','error'=>'Niet toegestaan']);
    exit;
}

$stmt = $pdo->prepare("UPDATE videos SET title=?, youtube_url=?, thumbnail_url=? WHERE id=?");
$stmt->execute([$title,$youtube_url,$thumbnail_url ?: null,$video_id]);

echo json_encode(['status'=>'success']);
