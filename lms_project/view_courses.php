<?php
require_once "config.php";
include("includes/header.php");
?>

<?php
// session_start();
// require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_role = $_SESSION['role'];
$user_name = $_SESSION['name'];

$sql = "SELECT courses.*, users.name AS instructor_name 
        FROM courses 
        JOIN users ON courses.instructor_id = users.id";

$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Courses</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>
</head>

<body>

    <div class="layout">

        <!-- Sidebar -->
       

        <!-- Main -->
        <div class="main">

            <div class="stats">
                <div class="stat-card">
                    <h3>Total Courses</h3>
                    <p><?php echo count($courses); ?></p>
                </div>

                <div class="stat-card">
                    <h3>User Role</h3>
                    <p><?php echo strtoupper($user_role); ?></p>
                </div>
            </div>

            <?php foreach ($courses as $course): ?>

                <div class="card">
                    <h3><?php echo htmlspecialchars($course['title']); ?></h3>

                    <p><?php echo htmlspecialchars($course['description']); ?></p>

                    <p><strong>Instructor:</strong>
                        <?php echo htmlspecialchars($course['instructor_name']); ?>
                    </p>

                    <br>

                    <a class="btn btn-primary"
                        href="course_detail.php?id=<?php echo $course['id']; ?>">
                        View Details
                    </a>

                </div>

            <?php endforeach; ?>

        </div>

    </div>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        }

        function toggleDarkMode() {
            document.body.classList.toggle('dark');
        }
    </script>

    <?php include("includes/footer.php"); ?>

</body>

</html>