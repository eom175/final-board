<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    exit;
}

require_once '../db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT name, password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $username = $user["name"];
    $passwordLength = strlen($user["password"]);
    $maskedPassword = str_repeat("*", $passwordLength);

    echo json_encode([
        'success' => true,
        'name' => $username,
        'password' => $maskedPassword
    ]);
} else {
    echo json_encode(['success' => false, 'message' => '사용자 정보를 찾을 수 없습니다.']);
}
?>
