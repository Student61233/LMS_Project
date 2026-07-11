<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<?php
//  session_start();
  ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - LMS</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style2.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="auth-wrapper">

    <!-- Left Branding Side -->
    <div class="auth-left">
        <div>
            <h1>Welcome Back 👋</h1>
            <p>Login to continue managing your courses.</p>
        </div>
    </div>

    <!-- Right Form Side -->
    <div class="auth-right">
        <div class="auth-box">
            <h2>Login</h2>

            <form action="control.php" method="POST">
                <input type="hidden" name="action" value="login">

                <div class="form-group">
                    <input type="email" name="email" placeholder=" " required>
                    <label>Email</label>
                </div>

                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder=" " required>
                    <label>Password</label>
                    <span class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>

                <button class="auth-btn">Login</button>
            </form>

            <p style="margin-top:15px;">
                Don't have an account?
                <a href="register.php">Register</a>
            </p>
        </div>
    </div>

</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>