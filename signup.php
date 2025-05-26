<h2 style="text-align: center;">Sign Up</h2>

<style>
    #signup-form {
        width: 400px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        background: #fff;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }

    #signup-form label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
    }

    #signup-form input {
        width: 100%;
        padding: 8px;
        margin-top: 4px;
        margin-bottom: 10px;
        box-sizing: border-box;
    }

    #check-dup {
        position: absolute;
        right: 40px;
        top: 75px;
        padding: 6px 10px;
    }

    .form-row {
        position: relative;
    }

    .btn-row {
        text-align: right;
        margin-top: 20px;
    }

    #signup-form button {
        padding: 6px 12px;
        margin-left: 5px;
        background-color: #ddd;
        border: none;
        font-weight: bold;
        cursor: pointer;
    }

    #signup-form button:hover {
        background-color: #ccc;
    }
</style>

<form id="signup-form" autocomplete="off">
    <div class="form-row">
        <label for="userid">User ID</label>
        <input type="text" name="userid" id="userid" required>
        <button type="button" id="check-dup">Duplicate Check</button>
    </div>

    <label for="signup_password">Password</label>
    <input type="password" name="signup_password" placeholder="Password" autocomplete="off" required>

    <label for="signup_confirm">Password Confirm</label>
    <input type="password" name="signup_confirm" placeholder="Password Confirm" autocomplete="off" required>

    <label for="name">Name</label>
    <input type="text" name="name" placeholder="Name" required>

    <label for="email">Email</label>
    <input type="email" name="email" placeholder="Email" required>

    <div class="btn-row">
        <button type="submit">Save</button>
        <button type="button" id="cancel-signup">Cancel</button>
    </div>
</form>

<script>
$(document).ready(function() {
    let isChecked = false;

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

    $('#signup-form').on('submit', function(e) {
        e.preventDefault();

        const pw = $('input[name="signup_password"]').val().trim();
        const confirm = $('input[name="signup_confirm"]').val().trim();

        if (!isChecked) {
            alert('아이디 중복 확인을 해주세요.');
            return;
        }

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
