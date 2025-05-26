<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>AJAX 게시판</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="login-section">
        <form id="login-form">
            <input type="text" name="userid" placeholder="아이디" required>
            <input type="password" name="password" placeholder="비밀번호" required>
            <button type="submit">로그인</button>
        </form>
    </div>

    <div id="content"></div>

    <script>
        $(document).ready(function() {
            $('#login-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'ajax/login.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#login-section').hide();
                            $('#content').load('list.php');
                        } else if (response.signup) {
                            alert('사용자를 찾을 수 없습니다. 회원가입 페이지로 이동합니다.');
                            $('#login-section').hide();
                            $('#content').load('signup.php');
                        } else {
                            alert('로그인 실패: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('로그인 중 오류 발생');
                    }
                });
            });
        });
    </script>
</body>
</html>
