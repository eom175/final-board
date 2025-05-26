<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>잘못된 접근입니다.</p>";
    exit;
}

$postId = intval($_GET['id']);

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

<style>
    .view-box {
        width: 600px;
        margin: 30px auto;
        padding: 20px;
        border: 1px solid #ccc;
        font-family: Arial, sans-serif;
        background-color: #fff;
    }

    .view-header {
        font-size: 18px;
        font-weight: bold;
        border-bottom: 1px solid #aaa;
        padding-bottom: 5px;
        margin-bottom: 10px;
    }

    .view-title {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .view-meta {
        text-align: right;
        margin-bottom: 15px;
        color: #555;
    }

    .view-content {
        white-space: pre-line;
        margin-bottom: 20px;
    }

    .view-buttons {
        text-align: center;
    }

    .view-buttons button {
        padding: 6px 12px;
        margin: 0 5px;
        background-color: #ddd;
        border: none;
        font-weight: bold;
        cursor: pointer;
    }

    .view-buttons button:hover {
        background-color: #ccc;
    }
</style>

<div class="view-box">
    <div class="view-header">Bulletin Board > Viewing Content</div>

    <div class="view-title">
        <strong>Title:</strong> <?= htmlspecialchars($post['title']) ?>
    </div>

    <div class="view-meta">
        <?= htmlspecialchars($post['name']) ?> &nbsp; | &nbsp; <?= date('d/m/Y', strtotime($post['created_at'])) ?>
    </div>

    <div class="view-content">
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>

    <div class="view-buttons">
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
    $('#list-btn').on('click', function() {
        $('#content').load('list.php');
    });

    $('#edit-btn').on('click', function() {
        const postId = $(this).data('id');
        $('#content').load('edit.php?id=' + postId);
    });

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

    $('#write-btn').on('click', function() {
        $('#content').load('write.php');
    });

    $('#logout-btn').on('click', function() {
        $.post('ajax/logout.php', function(response) {
            if (response.success) {
                location.reload();
            }
        }, 'json').fail(function() {
            alert('로그아웃 중 오류 발생');
        });
    });
});
</script>
