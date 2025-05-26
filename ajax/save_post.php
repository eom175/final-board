<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    exit;
}

require_once '../db.php';

$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$user_id = $_SESSION['user_id'];

if (isset($_POST['id'])) {
    // 수정
    $id = intval($_POST['id']);
    $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $title, $content, $id, $user_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'id' => $id]);
} else {
    // 새 글 작성
    $sql = "INSERT INTO posts (title, content, user_id, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $user_id);
    $stmt->execute();
    $newId = $stmt->insert_id;

    echo json_encode(['success' => true, 'id' => $newId]);
}
?>
