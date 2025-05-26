<?php
session_start();
require_once 'db.php';

// 최신 글 순으로 정렬
$sql = "
    SELECT posts.id, posts.title, users.name, posts.created_at
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
";
$result = $conn->query($sql);
?>

<h2 style="text-align: center;">Bulletin Board > List View</h2>

<style>
    table.board-table {
        width: 90%;
        margin: 0 auto;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }

    table.board-table th, table.board-table td {
        border-bottom: 1px solid #ccc;
        padding: 10px;
        text-align: left;
    }

    table.board-table th {
        background-color: #f5f5f5;
    }

    table.board-table td.title-cell {
        cursor: pointer;
        color: blue;
        text-decoration: underline;
    }

    .btn-area {
        width: 90%;
        margin: 20px auto;
        text-align: right;
    }

    .btn-area button {
        padding: 6px 12px;
        margin-left: 5px;
        background-color: #ddd;
        border: none;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-area button:hover {
        background-color: #ccc;
    }
</style>

<table class="board-table">
    <thead>
        <tr>
            <th style="width: 5%;">No.</th>
            <th style="width: 55%;">Title</th>
            <th style="width: 20%;">Name</th>
            <th style="width: 20%;">Date</th>
        </tr>
    </thead>
    <tbody>
        <?php $index = $result->num_rows; ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $index-- ?></td>
            <td class="title-cell" data-id="<?= $row['id'] ?>">
                <?= htmlspecialchars($row['title']) ?>
            </td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div class="btn-area">
    <button id="write-btn">Write</button>
    <button id="logout-btn">Logout</button>
</div>

<script>
$(document).ready(function() {
    // 글 제목 클릭 시 상세 보기
    $('.title-cell').on('click', function() {
        const postId = $(this).data('id');
        $('#content').load('view.php?id=' + postId);
    });

    // 글쓰기
    $('#write-btn').on('click', function() {
        $('#content').load('write.php');
    });

    // 로그아웃
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
