<?php
require_once "config.php";
include("includes/header.php");
?>
<?php
// session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>

<h2>Student Dashboard</h2>

<br><br>


 <button class="btn btn-primary" style="margin-left: 2rem;">
           <a href="view_courses.php" style="text-decoration: none;">View Courses</a>
        </button>




<br>



<?php include("includes/footer.php"); ?>

</body>
</html>