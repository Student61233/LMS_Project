<?php
require_once "config.php";
include("header.php");
?>

<?php
// session_start();
// require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$course_id = $_GET['id'] ?? '';

if (!$course_id) {
    die("Invalid Course ID");
}

/* ✅ Fetch Course with Instructor Name */
$sql = "SELECT courses.*, users.name AS instructor_name
        FROM courses
        JOIN users ON courses.instructor_id = users.id
        WHERE courses.id = :course_id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':course_id', $course_id);
$stmt->execute();
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    die("Course not found.");
}

/* ✅ Fetch Course Assignments */
$assignment_sql = "SELECT * FROM assignments WHERE course_id = :course_id";
$assignment_stmt = $conn->prepare($assignment_sql);
$assignment_stmt->bindParam(':course_id', $course_id);
$assignment_stmt->execute();
$assignments = $assignment_stmt->fetchAll(PDO::FETCH_ASSOC);

/* ✅ Check if Student is Enrolled */
$is_enrolled = false;
if ($_SESSION['role'] == 'student') {
    $enroll_sql = "SELECT * FROM enrollments WHERE student_id = :student_id AND course_id = :course_id";
    $enroll_stmt = $conn->prepare($enroll_sql);
    $enroll_stmt->bindParam(':student_id', $_SESSION['user_id']);
    $enroll_stmt->bindParam(':course_id', $course_id);
    $enroll_stmt->execute();
    if ($enroll_stmt->fetch()) {
        $is_enrolled = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Details</title>
</head>
<body>

<h2><?php echo htmlspecialchars($course['title']); ?></h2>
<p><strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor_name']); ?></p>
<p><?php echo htmlspecialchars($course['description']); ?></p>

<hr>

<!-- ✅ Assignments Section -->
<h3>Course Assignments</h3>

<?php if (count($assignments) > 0): ?>
    <?php foreach ($assignments as $assign): ?>
        <div style="border: 1px dashed gray; padding: 15px; margin-bottom: 10px;">
            <h4>Title: <?php echo htmlspecialchars($assign['title']); ?></h4>
            <p>Description: <?php echo htmlspecialchars($assign['description']); ?></p>

            <!-- File Upload Form (Only for Enrolled Students) -->
            <?php if ($_SESSION['role'] == 'student' && $is_enrolled): ?>
                
                <?php
                // Check if already submitted
                $sub_sql = "SELECT * FROM submissions WHERE assignment_id = :assignment_id AND student_id = :student_id";
                $sub_stmt = $conn->prepare($sub_sql);
                $sub_stmt->bindParam(':assignment_id', $assign['id']);
                $sub_stmt->bindParam(':student_id', $_SESSION['user_id']);
                $sub_stmt->execute();
                $already_submitted = $sub_stmt->fetch();
                ?>

                <?php if (!$already_submitted): ?>
                    <form action="control.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="submit_assignment">
                        <input type="hidden" name="assignment_id" value="<?php echo $assign['id']; ?>">
                        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                        
                        <label>Upload File (PDF, DOC, ZIP only):</label><br>
                        <input type="file" name="file" required><br><br>
                        
                        <button type="submit">Submit Assignment</button>
                    </form>
                <?php else: ?>
                    <p style="color: green; font-weight: bold;">Assignment Submitted ✅</p>
                <?php endif; ?>

            <?php elseif ($_SESSION['role'] == 'student' && !$is_enrolled): ?>
                <p style="color: red;">Enroll in this course to submit assignments.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No assignments found for this course.</p>
<?php endif; ?>

<br>
<a href="view_courses.php">Back to Courses</a>

<?php include("footer.php"); ?>

</body>
</html>