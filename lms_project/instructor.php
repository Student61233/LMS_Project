<?php
require_once "config.php";
include("includes/header.php");
?>

<?php
// session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Instructor Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <h2>Instructor Panel</h2>
   
</div>
<br>
<div class="container">

    <div class="section-title">Course Management</div>

    <div class="card">
        <a class="btn btn-primary" href="create_course.php">Create New Course</a>
        <a class="btn btn-danger" href="my_courses.php">My Courses</a>
        <a class="btn btn-success" href="view_courses.php">View All Courses</a>
    </div>

    <div class="section-title">Assignment Management</div>

    <div class="card">
        <a class="btn btn-primary" href="create_assignment.php">Create Assignment</a>
        <a class="btn btn-success" href="view_submissions.php">View Submissions</a>
    </div>

</div>

<?php include("includes/footer.php"); ?>

</body>
</html>