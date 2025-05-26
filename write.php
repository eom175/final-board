<!-- write.php -->
<h2>글쓰기</h2>
<form id="write-form">
    <input type="text" name="title" placeholder="제목" required><br>
    <textarea name="content" placeholder="내용" required></textarea><br>
    <button type="submit">등록</button>
</form>
<button id="cancel-btn">취소</button>

<script>
    $(document).ready(function() {
        $('#write-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'ajax/save_post.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json', // ✅ 반드시 추가해야 함!
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

        $('#cancel-btn').on('click', function() {
            $('#content').load('list.php');
        });
    });
</script>
