<!-- list.php -->
<h2>게시글 목록</h2>
<button id="write-btn">글쓰기</button>
<div id="posts">
    <!-- 게시글 목록이 여기에 로드됩니다 -->
</div>

<script>
    $(document).ready(function() {
        // 게시글 목록 로드
        function loadPosts() {
            $.ajax({
                url: 'ajax/load_posts.php',
                type: 'GET',
                success: function(data) {
                    $('#posts').html(data);
                },
                error: function() {
                    alert('게시글을 불러오는 데 실패했습니다.');
                }
            });
        }

        loadPosts();

        // 글쓰기 버튼 클릭 시
        $('#write-btn').on('click', function() {
            $('#content').load('write.php');
        });

        // 게시글 클릭 시 상세 보기
        $('#posts').on('click', '.post-title', function() {
            var postId = $(this).data('id');
            $('#content').load('view.php?id=' + postId);
        });
    });
</script>
