<?php
header('Content-Type: application/json');
require_once '../db.php';

if (!isset($_POST['userid'])) {
    echo json_encode(['exists' => false]);
    exit;
}

$userid = $_POST['userid'];

$sql = "SELECT id FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$stmt->store_result();

echo json_encode(['exists' => $stmt->num_rows > 0]);
?>
