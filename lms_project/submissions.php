<?php
require_once "config.php";
include("includes/header.php");
?>

<?php
// session_start();
// require_once "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.php");
    exit();
}

/* ✅ Fetch All Submissions for Instructor's Courses */
$sql = "SELECT submissions.*, users.name AS student_name, assignments.title AS assignment_title, courses.title AS course_title
        FROM submissions
        JOIN users ON submissions.student_id = users.id
        JOIN assignments ON submissions.assignment_id = assignments.id
        JOIN courses ON assignments.course_id = courses.id
        WHERE courses.instructor_id = :instructor_id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':instructor_id', $_SESSION['user_id']);
$stmt->execute();
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Submissions</title>
</head>
<body>

<h2>Student Submissions</h2>

<?php if (count($submissions) > 0): ?>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Course</th>
                <th>Assignment</th>
                <th>Student Name</th>
                <th>File</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($submissions as $sub): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sub['course_title']); ?></td>
                    <td><?php echo htmlspecialchars($sub['assignment_title']); ?></td>
                    <td><?php echo htmlspecialchars($sub['student_name']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($sub['file_path']); ?>" download>Download File</a></td>
                    <td><?php echo $sub['submitted_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No submissions found.</p>
<?php endif; ?>

<br>
<a href="instructor.php">Back to Dashboard</a>

<?php include("includes/footer.php"); ?>

</body>
</html>