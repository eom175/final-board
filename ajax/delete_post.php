<?php
session_start();
header('Content-Type: application/json');

// 1. 로그인 여부 확인
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    exit;
}

// 2. POST로 전달된 게시글 ID 확인
if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => '게시글 ID가 없습니다.']);
    exit;
}

$post_id = intval($_POST['id']);
$user_id = intval($_SESSION['user_id']);

// 3. DB 연결
require_once '../db.php'; // DB 연결 정보 (host, user, password, dbname 등 포함되어야 함)

// 4. 해당 글이 로그인한 사용자의 것인지 확인
$sql = "SELECT id FROM posts WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => '삭제 권한이 없습니다.']);
    exit;
}

// 5. 삭제 실행
$delete_sql = "DELETE FROM posts WHERE id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("i", $post_id);

if ($delete_stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '삭제 실패: ' . $conn->error]);
}
?>
