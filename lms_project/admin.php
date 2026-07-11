<?php
require_once "config.php";
include("includes/header.php");
?>

<?php
// session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h2>Admin Dashboard</h2>

<!-- <a href="view_courses.php">View Courses</a> -->
<br>
<button class="btn btn-primary" >
            <i class="fas fa-save"></i><a href="view_courses.php" style="text-decoration: none;">View Courses</a>
        </button>
<!-- <a href="view_courses.php">View Courses</a> -->

<br><br>
<?php include("includes/footer.php"); ?>

</body>
</html>