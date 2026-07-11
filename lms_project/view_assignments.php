<?php
require_once "config.php";
include("includes/header.php");
?>

<?php
// session_start();
require_once "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$course_id = $_GET['course_id'] ?? '';

if (!$course_id) {
    die("Invalid Course ID");
}

/* ✅ Check enrollment */
$check = $conn->prepare("SELECT * FROM enrollments 
                         WHERE student_id = :sid AND course_id = :cid");
$check->execute([
    ':sid' => $_SESSION['user_id'],
    ':cid' => $course_id
]);

if (!$check->fetch()) {
    die("You are not enrolled in this course.");
}

/* ✅ Get all assignments */
$sql = "SELECT * FROM assignments WHERE course_id = :cid ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':cid', $course_id);
$stmt->execute();
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Assignments</title>
</head>
<body>

<h2>Course Assignments</h2>

<?php if (count($assignments) > 0): ?>

    <?php foreach ($assignments as $a): ?>

        <div style="border:1px solid #000; padding:10px; margin-bottom:10px;">

            <h3><?php echo htmlspecialchars($a['title']); ?></h3>
            <p><?php echo htmlspecialchars($a['description']); ?></p>

            <!-- ✅ Check if already submitted -->
            <?php
            $s = $conn->prepare("SELECT * FROM submissions 
                                 WHERE assignment_id = :aid AND student_id = :sid");
            $s->execute([
                ':aid' => $a['id'],
                ':sid' => $_SESSION['user_id']
            ]);
            $submission = $s->fetch(PDO::FETCH_ASSOC);
            ?>

            <?php if ($submission): ?>
                <p style="color:green;">
                    ✅ Submitted: 
                    <a href="<?php echo $submission['file_path']; ?>" target="_blank">
                        View File
                    </a>
                </p>
            <?php else: ?>

                <form action="control.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="submit_assignment">
                    <input type="hidden" name="assignment_id" value="<?php echo $a['id']; ?>">
                    <input type="file" name="file" required>
                    <button type="submit">Upload</button>
                </form>

            <?php endif; ?>

        </div>

    <?php endforeach; ?>

<?php else: ?>
    <p>No assignments yet.</p>
<?php endif; ?>

<br>
<input type="hidden" name="course_id_redirect" value="<?php echo $course_id; ?>">

<br>
<a href="view_courses.php">Back to Courses</a>


<?php include("includes/footer.php"); ?>

</body>
</html>