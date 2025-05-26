<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p>로그인이 필요합니다.</p>";
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>잘못된 접근입니다.</p>";
    exit;
}

$postId = intval($_GET['id']);
$userId = intval($_SESSION['user_id']);

$sql = "SELECT * FROM posts WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $postId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<p>글을 찾을 수 없거나 수정 권한이 없습니다.</p>";
    exit;
}

$post = $result->fetch_assoc();
?>

<style>
    .edit-box {
        width: 600px;
        margin: 30px auto;
        padding: 20px;
        border: 1px solid #ccc;
        background: #fff;
        font-family: Arial, sans-serif;
    }

    .edit-header {
        font-size: 18px;
        font-weight: bold;
        border-bottom: 1px solid #aaa;
        padding-bottom: 5px;
        margin-bottom: 20px;
    }

    .edit-form label {
        display: inline-block;
        width: 100px;
        font-weight: bold;
        margin-top: 10px;
    }

    .edit-form input[type="text"],
    .edit-form input[type="password"],
    .edit-form textarea {
        width: 80%;
        padding: 6px;
        margin-bottom: 10px;
        box-sizing: border-box;
    }

    .edit-form textarea {
        height: 100px;
        resize: vertical;
    }

    .edit-buttons {
        text-align: right;
        margin-top: 20px;
    }

    .edit-buttons button {
        padding: 6px 12px;
        margin-left: 5px;
        background-color: #ddd;
        border: none;
        font-weight: bold;
        cursor: pointer;
    }

    .edit-buttons button:hover {
        background-color: #ccc;
    }
</style>

<div class="edit-box">
    <div class="edit-header">Bulletin Board > Editing</div>

    <form id="edit-form" class="edit-form">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">

        <label>Name</label>
        <input type="text" value="<?= htmlspecialchars($_SESSION['name']) ?>" disabled><br>

        <label>Password</label>
        <input type="password" value="******" disabled><br>

        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br>

        <label>Content</label>
        <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea><br>

        <div class="edit-buttons">
            <button type="submit">Save</button>
            <button type="button" id="list-btn">List</button>
            <button type="button" id="logout-btn">Logout</button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#edit-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax/save_post.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('수정되었습니다.');
                    $('#content').load('view.php?id=' + response.id);
                } else {
                    alert('수정 실패: ' + response.message);
                }
            },
            error: function() {
                alert('수정 중 오류가 발생했습니다.');
            }
        });
    });

    $('#list-btn').on('click', function() {
        $('#content').load('list.php');
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
