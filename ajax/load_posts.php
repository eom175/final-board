<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

// DB 연결
require_once '../db.php';

// 게시글 목록 조회 (작성자 이름 포함)
$sql = "
    SELECT posts.id, posts.title, posts.created_at, users.name
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
";

$result = $conn->query($sql);

// 출력
if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        $post_id = htmlspecialchars($row['id']);
        $title = htmlspecialchars($row['title']);
        $name = htmlspecialchars($row['name']);
        $created = htmlspecialchars($row['created_at']);

        echo "<li>
            <span class='post-title' data-id='{$post_id}' style='cursor:pointer; color:blue; text-decoration:underline;'>
                [{$post_id}] {$title}
            </span> - 작성자: {$name} / 작성일: {$created}
        </li>";
    }
    echo "</ul>";
} else {
    echo "<p>게시글이 없습니다.</p>";
}
?>
