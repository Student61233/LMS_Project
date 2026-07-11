<?php
require_once "config.php";
include("includes/header.php");

/* Get Student Enrollments */
$sql = "SELECT courses.id, courses.title, courses.description
        FROM enrollments
        JOIN courses ON enrollments.course_id = courses.id
        WHERE enrollments.student_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3><i class="fas fa-user-graduate"></i> My Enrollments</h3>

<?php if(count($courses) > 0): ?>

    <?php foreach ($courses as $course): ?>

        <div class="card">
            <h3><?= htmlspecialchars($course['title']); ?></h3>

            <p><?= htmlspecialchars($course['description']); ?></p>

            <a class="btn btn-primary"
               href="view_assignments.php?course_id=<?= $course['id']; ?>">
               <i class="fas fa-eye"></i> View Assignments
            </a>
        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="card">
        <p>You are not enrolled in any courses yet.</p>
    </div>

<?php endif; ?>

<?php include("includes/footer.php"); ?>