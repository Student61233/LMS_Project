<?php
require_once "config.php";
include("includes/header.php");

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    echo "<div class='card'>Course not found.</div>";
    include("includes/footer.php");
    exit();
}
?>

<div class="card">

    <h3><i class="fas fa-edit"></i> Edit Course</h3>

    <form action="control.php" method="POST">

        <input type="hidden" name="action" value="update_course">
        <input type="hidden" name="course_id" value="<?= $course['id']; ?>">
            <br>
        <div class="form-group">
            <input type="text" name="title" value="<?= htmlspecialchars($course['title']); ?>" required placeholder=" ">
            <label>Course Title</label>
        </div>
        <br>
        <div class="form-group">
            <textarea name="description" required placeholder=" "><?= htmlspecialchars($course['description']); ?></textarea>
            <label>Description</label>
        </div>
            <br>
        <button class="btn btn-primary">
            <i class="fas fa-save"></i> Update Course
        </button>

        <a href="my_courses.php" class="btn btn-danger">
            <i class="fas fa-arrow-left"></i> Back
        </a>

    </form>

</div>

<?php include("includes/footer.php"); ?>