<?php
session_start();
require_once 'db.php';

// 1. 로그인 확인
if (!isset($_SESSION['user_id'])) {
    echo "<p>로그인이 필요합니다.</p>";
    exit;
}

// 2. 유효한 post id 검사
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>잘못된 접근입니다.</p>";
    exit;
}

$postId = intval($_GET['id']);
$userId = intval($_SESSION['user_id']);

// 3. DB에서 글 조회 (작성자 확인)
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

<h2>글 수정</h2>
<form id="edit-form">
    <input type="hidden" name="id" value="<?= $post['id'] ?>">
    <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br>
    <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea><br>
    <button type="submit">수정</button>
</form>
<button id="cancel-btn">취소</button>

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

        $('#cancel-btn').on('click', function() {
            $('#content').load('view.php?id=<?= $post['id'] ?>');
        });
    });
</script>
