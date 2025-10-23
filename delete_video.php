<?php

require 'config.php';
if (!is_logged_in()) { http_response_code(403); exit; }

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM videos WHERE id=?");
$stmt->execute([$id]);
$video = $stmt->fetch();
if (!$video) { http_response_code(404); exit; }

$user = current_user($pdo);
if (!$user['is_admin'] && $video['created_by'] != $user['id']) {
    http_response_code(403); exit;
}

$stmt = $pdo->prepare("DELETE FROM videos WHERE id=?");
$stmt->execute([$id]);

header('Location: category.php?slug=' . urlencode($_GET['slug'] ?? ''));
exit;
