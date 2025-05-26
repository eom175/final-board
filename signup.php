<!-- signup.php -->
<h2>Sign Up</h2>
<form id="signup-form" autocomplete="off">
    <input type="text" name="userid" id="userid" placeholder="User ID" required>
    <button type="button" id="check-dup">Duplicate Check</button><br><br>

    <input type="password" name="signup_password" placeholder="Password" autocomplete="off"><br>
    <input type="password" name="signup_confirm" placeholder="Password Confirm" autocomplete="off"><br>
    <input type="text" name="name" placeholder="Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>

    <button type="submit">Save</button>
    <button type="button" id="cancel-signup">Cancel</button>
</form>

<script>
$(document).ready(function() {
    let isChecked = false;

    // 아이디 중복 확인
    $('#check-dup').on('click', function() {
        const userid = $('#userid').val().trim();
        if (userid === '') {
            alert('User ID를 입력하세요.');
            return;
        }

        $.post('ajax/check_userid.php', { userid: userid }, function(response) {
            if (response.exists) {
                alert('이미 사용 중인 아이디입니다.');
                isChecked = false;
            } else {
                alert('사용 가능한 아이디입니다.');
                isChecked = true;
            }
        }, 'json');
    });

    // 회원가입 제출
    $('#signup-form').on('submit', function(e) {
        e.preventDefault();

        if (!isChecked) {
            alert('아이디 중복 확인을 해주세요.');
            return;
        }

        const pw = $('input[name="signup_password"]').val().trim();
        const confirm = $('input[name="signup_confirm"]').val().trim();


        console.log("pw =", `[${pw}]`, "confirm =", `[${confirm}]`);

        if (pw !== confirm) {
            alert('비밀번호가 일치하지 않습니다.');
            return;
        }

        $.ajax({
            url: 'ajax/signup.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert('회원가입 완료! 로그인해주세요.');
                    $('#login-section').show();
                    $('#content').empty();
                } else {
                    alert('회원가입 실패: ' + res.message);
                }
            },
            error: function() {
                alert('회원가입 중 오류 발생');
            }
        });
    });

    $('#cancel-signup').on('click', function() {
        $('#login-section').show();
        $('#content').empty();
    });
});
</script>
