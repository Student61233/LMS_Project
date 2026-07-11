 <?php
require_once "config.php";
include("includes/header.php");

$stmt = $conn->prepare("SELECT * FROM courses WHERE instructor_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3><i class="fas fa-book"></i> My Courses</h3>

<?php foreach ($courses as $course): ?>

<div class="card">
    <h3><?= htmlspecialchars($course['title']); ?></h3>
    <p><?= htmlspecialchars($course['description']); ?></p>
    <br>

    <a class="btn btn-primary" 
       href="edit_course.php?id=<?= $course['id']; ?>">
       <i class="fas fa-edit"></i> Edit
    </a>

    <form action="control.php" method="POST" style="display:inline;">
        <input type="hidden" name="action" value="delete_course">
        <input type="hidden" name="course_id" value="<?= $course['id']; ?>">
        <button class="btn btn-danger">
            <i class="fas fa-trash"></i> Delete
        </button>
    </form>
</div>

<?php endforeach; ?>

<?php include("includes/footer.php"); ?>

</body>

</html>