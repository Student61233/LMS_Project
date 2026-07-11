<?php
require_once "config.php";
include("includes/header.php");
?>

<div class="card">
    <h3><i class="fas fa-plus-circle"></i> Create New Course</h3>

    <form action="control.php" method="POST">
        <input type="hidden" name="action" value="create_course">

        <label>Course Title</label>
        <input type="text" name="title" required>

        <label style="margin-left: 10rem;">Description</label>
        <textarea name="description" required></textarea>
        <br><br>
        <button class="btn btn-primary" style="margin-left: 2rem;">
            <i class="fas fa-save"></i> Create Course
        </button>
    </form>
</div>

<?php include("includes/footer.php"); ?>