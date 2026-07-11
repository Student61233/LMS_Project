<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_role = $_SESSION['role'];
$user_name = $_SESSION['name'];
?>


<!DOCTYPE html>
<html>
<head>
    <title>LMS System</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="Style.css">
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="layout">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>LMS SYSTEM</h2>

        <a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="view_courses.php"><i class="fas fa-book"></i> Courses</a>

        <?php if ($user_role == 'student'): ?>
            <a href="my_enrollments.php">
                <i class="fas fa-user-graduate"></i> My Enrollments
            </a>
        <?php endif; ?>

        <?php if ($user_role == 'instructor'): ?>
            <a href="create_course.php">
                <i class="fas fa-plus"></i> Create Course
            </a>

            <a href="create_assignment.php">
                <i class="fas fa-tasks"></i> Create Assignment
            </a>

            <a href="view_submissions.php">
                <i class="fas fa-file-upload"></i> Submissions
            </a>
        <?php endif; ?>

        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h3>LMS Dashboard</h3>
            <span>Welcome, <?php echo htmlspecialchars($user_name); ?></span>
        </div>