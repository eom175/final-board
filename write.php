<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<p>로그인이 필요합니다.</p>";
    exit;
}
?>

<style>
.write-box {
    width: 600px;
    margin: 30px auto;
    padding: 20px;
    border: 1px solid #ccc;
    background: #fff;
    font-family: Arial, sans-serif;
}

.write-header {
    font-size: 18px;
    font-weight: bold;
    border-bottom: 1px solid #aaa;
    padding-bottom: 5px;
    margin-bottom: 20px;
}

.write-form label {
    display: inline-block;
    width: 100px;
    font-weight: bold;
    margin-top: 10px;
}

.write-form input[type="text"],
.write-form input[type="password"],
.write-form textarea {
    width: 80%;
    padding: 6px;
    margin-bottom: 10px;
    box-sizing: border-box;
}

.write-form textarea {
    height: 100px;
    resize: vertical;
}

.write-buttons {
    text-align: right;
    margin-top: 20px;
}

.write-buttons button {
    padding: 6px 12px;
    margin-left: 5px;
    background-color: #ddd;
    border: none;
    font-weight: bold;
    cursor: pointer;
}

.write-buttons button:hover {
    background-color: #ccc;
}
</style>

<div class="write-box">
    <div class="write-header">Bulletin Board > Writing</div>

    <form id="write-form" class="write-form">
        <label>Name</label>
        <input type="text" value="<?= htmlspecialchars($_SESSION['name']) ?>" disabled><br>

        <label>Password</label>
        <input type="password" value="******" disabled><br>

        <label>Title</label>
        <input type="text" name="title" placeholder="제목" required><br>

        <label>Content</label>
        <textarea name="content" placeholder="내용" required></textarea><br>

        <div class="write-buttons">
            <button type="submit">Save</button>
            <button type="button" id="list-btn">List</button>
            <button type="button" id="logout-btn">Logout</button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#write-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax/save_post.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('등록되었습니다.');
                    $('#content').load('list.php');
                } else {
                    alert('등록 실패: ' + response.message);
                }
            },
            error: function() {
                alert('등록 중 오류가 발생했습니다.');
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
