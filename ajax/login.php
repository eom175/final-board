<?php
session_start();
header('Content-Type: application/json');

if (!isset($_POST['userid'], $_POST['password'])) {
    echo json_encode(['success' => false, 'message' => '입력 누락']);
    exit;
}

$userid = $_POST['userid'];
$password = $_POST['password'];

require_once '../db.php';

$sql = "SELECT * FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'signup' => true]);
    exit;
}

$user = $result->fetch_assoc();

// ✅ 해싱 대신 평문 비교
if ($password === $user['password']) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['userid']  = $user['userid'];
    $_SESSION['name']    = $user['name'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '비밀번호가 틀렸습니다.']);
}
?>
