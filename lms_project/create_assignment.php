<?php
require_once "config.php";
include("includes/header.php");
?>

<div class="card">
    <h3><i class="fas fa-tasks"></i> Create Assignment</h3>

    <form action="control.php" method="POST">
        <input type="hidden" name="action" value="create_assignment">
        <br>

        <label>Select Course</label>
        <select name="course_id" required>
            <?php
            $courses = $conn->query("SELECT * FROM courses WHERE instructor_id = ".$_SESSION['user_id']);
            foreach ($courses as $course):
            ?>
                <option value="<?= $course['id']; ?>">
                    <?= htmlspecialchars($course['title']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        

        <label style="margin-left: 10rem;">Assignment Title</label>
        <input type="text" name="title" required>
        <br><br>

        <label>Description</label>
        <textarea name="description" required ></textarea>
        


        <button class="btn btn-primary" style="margin-left: 10rem;">
            <i class="fas fa-save"></i> Create Assignment
        </button>

        <br><br>
<button class="btn btn-danger" style="margin-left: 2rem;">
            <i class="fas fa-save"></i> Back to Dashboard
        </button>
    </form>
</div>

<?php include("includes/footer.php"); ?>

</body>
</html>