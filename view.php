<?php
session_start();
require_once 'db.php';

// 게시글 ID 확인
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>잘못된 접근입니다.</p>";
    exit;
}

$postId = intval($_GET['id']);

// 게시글 + 작성자 정보 조회
$sql = "
    SELECT posts.*, users.name
    FROM posts
    JOIN users ON posts.user_id = users.id
    WHERE posts.id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<p>게시글을 찾을 수 없습니다.</p>";
    exit;
}

$post = $result->fetch_assoc();

$currentUserId = $_SESSION['user_id'] ?? null;
$isOwner = ($currentUserId == $post['user_id']);
?>

<!-- 게시글 상세 화면 -->
<div class="post-view">
    <h3><strong>Title:</strong> <?= htmlspecialchars($post['title']) ?></h3>
    <div style="text-align: right; margin-bottom: 10px;">
        <?= htmlspecialchars($post['name']) ?> |
        <?= date('d/m/Y', strtotime($post['created_at'])) ?>
    </div>

    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>

    <div style="margin-top: 20px;">
        <button id="list-btn">List</button>

        <?php if ($isOwner): ?>
            <button id="edit-btn" data-id="<?= $post['id'] ?>">Edit</button>
            <button id="delete-btn" data-id="<?= $post['id'] ?>">Delete</button>
        <?php endif; ?>

        <button id="write-btn">Write</button>
        <button id="logout-btn">Logout</button>
    </div>
</div>

<script>
$(document).ready(function() {
    // 목록
    $('#list-btn').on('click', function() {
        $('#content').load('list.php');
    });

    // 수정
    $('#edit-btn').on('click', function() {
        const postId = $(this).data('id');
        $('#content').load('edit.php?id=' + postId);
    });

    // 삭제
    $('#delete-btn').on('click', function() {
        const postId = $(this).data('id');
        if (confirm('정말 삭제하시겠습니까?')) {
            $.post('ajax/delete_post.php', { id: postId }, function(response) {
                if (response.success) {
                    alert('삭제되었습니다.');
                    $('#content').load('list.php');
                } else {
                    alert('삭제 실패: ' + response.message);
                }
            }, 'json').fail(function() {
                alert('삭제 중 오류가 발생했습니다.');
            });
        }
    });

    // 글쓰기
    $('#write-btn').on('click', function() {
        $('#content').load('write.php');
    });

    // 로그아웃
    $('#logout-btn').on('click', function() {
        $.post('ajax/logout.php', function(response) {
            if (response.success) {
                location.reload();  // 또는 $('#login-section').show(); $('#content').empty();
            }
        }, 'json').fail(function() {
            alert('로그아웃 중 오류 발생');
        });
    });
});
</script>
