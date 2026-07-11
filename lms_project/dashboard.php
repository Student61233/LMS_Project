<?php
require_once "config.php";
include("includes/header.php");

$user_role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

/* ================= ADMIN STATS ================= */
if ($user_role == 'admin') {

    $totalCourses = $conn->query("SELECT COUNT(*) FROM courses")->fetchColumn();
    $totalStudents = $conn->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
    $totalInstructors = $conn->query("SELECT COUNT(*) FROM users WHERE role='instructor'")->fetchColumn();
    $totalEnrollments = $conn->query("SELECT COUNT(*) FROM enrollments")->fetchColumn();
}

/* ================= INSTRUCTOR STATS ================= */
if ($user_role == 'instructor') {

    $totalCourses = $conn->prepare("SELECT COUNT(*) FROM courses WHERE instructor_id = ?");
    $totalCourses->execute([$user_id]);
    $totalCourses = $totalCourses->fetchColumn();

    $totalAssignments = $conn->query("SELECT COUNT(*) FROM assignments")->fetchColumn();
    $totalSubmissions = $conn->query("SELECT COUNT(*) FROM submissions")->fetchColumn();
}

/* ================= STUDENT STATS ================= */
if ($user_role == 'student') {

    $totalEnrollments = $conn->prepare("SELECT COUNT(*) FROM enrollments WHERE student_id = ?");
    $totalEnrollments->execute([$user_id]);
    $totalEnrollments = $totalEnrollments->fetchColumn();

    $totalAssignments = $conn->query("SELECT COUNT(*) FROM assignments")->fetchColumn();
}
?>

<h3><i class="fas fa-chart-line"></i> Dashboard</h3>

<div class="stats">

<?php if ($user_role == 'admin'): ?>

    <div class="stat-card">
        <h3>Total Courses</h3>
        <p><?= $totalCourses ?></p>
    </div>

    <div class="stat-card">
        <h3>Total Students</h3>
        <p><?= $totalStudents ?></p>
    </div>

    <div class="stat-card">
        <h3>Total Instructors</h3>
        <p><?= $totalInstructors ?></p>
    </div>

    <div class="stat-card">
        <h3>Total Enrollments</h3>
        <p><?= $totalEnrollments ?></p>
    </div>

<?php elseif ($user_role == 'instructor'): ?>

    <div class="stat-card">
        <h3>My Courses</h3>
        <p><?= $totalCourses ?></p>
    </div>

    <div class="stat-card">
        <h3>Total Assignments</h3>
        <p><?= $totalAssignments ?></p>
    </div>

    <div class="stat-card">
        <h3>Student Submissions</h3>
        <p><?= $totalSubmissions ?></p>
    </div>

<?php elseif ($user_role == 'student'): ?>

    <div class="stat-card">
        <h3>My Enrollments</h3>
        <p><?= $totalEnrollments ?></p>
    </div>

    <div class="stat-card">
        <h3>Total Assignments</h3>
        <p><?= $totalAssignments ?></p>
    </div>

<?php endif; ?>

</div>

<?php include("includes/footer.php"); ?>