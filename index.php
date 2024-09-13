<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #b0c4de;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .main-div {
            background-color: #dfe3ee;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: absolute;
            padding: 20px;
            border-radius: 15px;
            width: 1250px;
            height: 530px;
            margin-left: 3%;
            margin-right: 3%;
        }
        .div-form {
            text-align: center;
            background-color: #3b5998;
            padding: 20px;
            border-radius: 40px;
            margin-left: 15px;
            margin-right: 15px;
            height: 93%;
        }
        .div-form img {
            width: 70px;
            margin-bottom: 20px;
        }
        .admin-login {
            font-size: 50px;
            margin-top: 8px;
            margin-bottom: 20px;
            color: white;
        }
        .form-group {
            margin-bottom: 15px;
            position: relative;
        }
        .form-group input {
            height: 50px;
            width: 90%;
            padding: 10px 50px 10px 10px; 
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        #logButton {
            background-color: #6c83a0;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            width: 80%;
        }
        .login-link {
            color: white;
            font-size: 14px;
            display: block;
            margin-top: 10px;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 20px;
        }
        .toggle-password {
            position: absolute;
            right: 75px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 24px;
            color: #3b5998;
            user-select: none;
        }
    </style>
</head>
<body>
    <div class="main-div">
        <div class="div-form" id="login-form">
            <img src="user.png" alt="User Icon">
            <h1 class="admin-login">Admin</h1>
            <form method="post" action="login.php">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Enter Username" required>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Enter Password" required>
                    <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>
                <div class="form-group">
                    <input type="submit" id="logButton" value="Log In">
                </div>
            </form>

            <div class="error">
                <?php
                session_start();
                if (isset($_SESSION['error'])) {
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                }
                ?>
            </div>

            <div class="error" id="countdown"></div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.querySelector('.toggle-password');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash'); 
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye'); 
            }
        }

        // Lockout countdown script
        var lockoutTime = <?php echo isset($_SESSION['lockout_time']) ? ($_SESSION['lockout_time'] - time()) : 0; ?>;
        if (lockoutTime > 0) {
            var countdownElement = document.getElementById('countdown');
            var countdownInterval = setInterval(function() {
                if (lockoutTime <= 0) {
                    clearInterval(countdownInterval);
                    countdownElement.textContent = '';
                    location.reload();
                } else {
                    countdownElement.textContent = 'Account locked. Please try again in ' + lockoutTime + ' seconds.';
                    lockoutTime--;
                }
            }, 1000);
        }
    </script>
</body>
</html>
