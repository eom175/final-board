<?php
session_start();
header('Content-Type: application/json');

// signup_password, signup_confirm 으로 받음
if (!isset($_POST['userid'], $_POST['signup_password'], $_POST['signup_confirm'], $_POST['name'], $_POST['email'])) {
    echo json_encode(['success' => false, 'message' => '입력값 누락']);
    exit;
}

// 비밀번호 확인
if ($_POST['signup_password'] !== $_POST['signup_confirm']) {
    echo json_encode(['success' => false, 'message' => '비밀번호가 일치하지 않습니다.']);
    exit;
}

$userid = $_POST['userid'];
$password = $_POST['signup_password'];  // 평문 저장 (보안 신경 안 쓰기로 했으니 해시 X)
$name = $_POST['name'];
$email = $_POST['email'];

require_once '../db.php';

// 아이디 중복 검사
$check = $conn->prepare("SELECT id FROM users WHERE userid = ?");
$check->bind_param("s", $userid);
$check->execute();
$check_result = $check->get_result();
if ($check_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => '이미 존재하는 아이디입니다.']);
    exit;
}

// 사용자 삽입
$sql = "INSERT INTO users (userid, password, name, email) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $userid, $password, $name, $email);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'DB 오류: ' . $conn->error]);
}
?>
