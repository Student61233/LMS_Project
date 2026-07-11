<?php
require_once "config.php";
include("includes/header.php");

$sql = "SELECT submissions.*, users.name AS student_name
        FROM submissions
        JOIN users ON submissions.student_id = users.id
        JOIN assignments ON submissions.assignment_id = assignments.id
        JOIN courses ON assignments.course_id = courses.id
        WHERE courses.instructor_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$subs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<br>

<h3><i class="fas fa-file-upload"></i> Student Submissions</h3>
<br>

<?php foreach ($subs as $sub): ?>

<div class="card">
    <p><strong>Student:</strong> <?= htmlspecialchars($sub['student_name']); ?></p>
<br>
    <a class="btn btn-primary" 
       href="<?= $sub['file_path']; ?>" target="_blank">
       <i class="fas fa-eye"></i> View File
    </a>
</div>

<?php endforeach; ?>

<?php include("includes/footer.php"); ?>

</body>
</html>