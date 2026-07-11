<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Register - LMS</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Main CSS -->
    <link rel="stylesheet" href="style2.css">

    <!-- FontAwesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="auth-wrapper">

    <!-- Left Branding Section -->
    <div class="auth-left">
        <div>
            <h1>Create Account 🚀</h1>
            <p>Join our LMS and start your learning journey today.</p>
        </div>
    </div>

    <!-- Right Form Section -->
    <div class="auth-right">
        <div class="auth-box">
            <h2>Register</h2>

            <form action="control.php" method="POST">
                <input type="hidden" name="action" value="register">

                <!-- Name -->
                <div class="form-group">
                    <input type="text" name="name" placeholder=" " required>
                    <label>Full Name</label>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <input type="email" name="email" placeholder=" " required>
                    <label>Email</label>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder=" " required>
                    <label>Password</label>
                    <span class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>

                <!-- Role -->
                <div class="form-group">
                    <select name="role" required>
                        <option value="" disabled selected hidden></option>
                        <option value="student">Student</option>
                        <option value="instructor">Instructor</option>
                         <option value="admin">Admin</option>
                    </select>
                    <label>Register As</label>
                </div>

                <button class="auth-btn">Create Account</button>
            </form>

            <p style="margin-top:15px;">
                Already have an account?
                <a href="login.php">Login</a>
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