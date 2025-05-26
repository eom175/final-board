<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>AJAX 게시판</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
        }
        #login-section {
            width: 300px;
            margin: 100px auto;
            padding: 30px;
            border: 1px solid #ccc;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        #login-section h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        #login-form input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        #login-form button {
            width: 100%;
            padding: 10px;
            background-color: #ddd;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }
        #login-form button:hover {
            background-color: #ccc;
        }
    </style>
</head>
<body>
    <div id="login-section">
        <h2>Login</h2>
        <form id="login-form">
            <label>User ID</label>
            <input type="text" name="userid" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
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
