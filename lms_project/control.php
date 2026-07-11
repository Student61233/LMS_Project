<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'] ?? '';

    /* =========================
       ✅ LOGIN LOGIC
    ==========================*/
    if ($action == "login") {

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin.php");
            } elseif ($user['role'] == 'instructor') {
                header("Location: dashboard.php");
            } else {
                header("Location: student.php");
            }
            exit();
        } else {
            echo "Invalid Email or Password ❌";
        }
    }

    /* =========================
       ✅ REGISTER LOGIC
    ==========================*/ elseif ($action == "register") {

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        try {
            $sql = "INSERT INTO users (name, email, password, role) 
                    VALUES (:name, :email, :password, :role)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);

            $stmt->execute();

            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
        }
    }

    /* =========================
       ✅ CREATE COURSE
    ==========================*/ elseif ($action == "create_course") {

        if ($_SESSION['role'] != 'instructor') {
            die("Access Denied");
        }

        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $instructor_id = $_SESSION['user_id'];

        $sql = "INSERT INTO courses (title, description, instructor_id) 
                VALUES (:title, :description, :instructor_id)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':instructor_id', $instructor_id);

        $stmt->execute();

        header("Location: my_courses.php");
        exit();
    }

    /* =========================
       ✅ UPDATE COURSE
    ==========================*/ elseif ($action == "update_course") {

        if ($_SESSION['role'] != 'instructor') {
            die("Access Denied");
        }

        $course_id = $_POST['course_id'];
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $instructor_id = $_SESSION['user_id'];

        $sql = "UPDATE courses 
                SET title = :title, description = :description
                WHERE id = :course_id 
                AND instructor_id = :instructor_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':instructor_id', $instructor_id);

        $stmt->execute();

        header("Location: my_courses.php");
        exit();
    }
    /* =========================
   ✅ CREATE ASSIGNMENT
==========================*/ elseif ($action == "create_assignment") {

        if ($_SESSION['role'] != 'instructor') {
            die("Access Denied");
        }

        $course_id = $_POST['course_id'];
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);

        $sql = "INSERT INTO assignments (course_id, title, description)
            VALUES (:course_id, :title, :description)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);

        $stmt->execute();

        header("Location: instructor.php");
        exit();
    }

    /* =========================
       ✅ DELETE COURSE
    ==========================*/ elseif ($action == "delete_course") {

        if ($_SESSION['role'] != 'instructor') {
            die("Access Denied");
        }

        $course_id = $_POST['course_id'];
        $instructor_id = $_SESSION['user_id'];

        $sql = "DELETE FROM courses 
                WHERE id = :course_id 
                AND instructor_id = :instructor_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':instructor_id', $instructor_id);

        $stmt->execute();

        header("Location: my_courses.php");
        exit();
    }

    /* ====================================
       ✅ SUBMIT ASSIGNMENT (FILE UPLOAD)
    ======================================*/ elseif ($action == "submit_assignment") {

        if ($_SESSION['role'] != 'student') {
            die("Access Denied");
        }

        $student_id = $_SESSION['user_id'];
        $assignment_id = $_POST['assignment_id'];
        $course_id = $_POST['course_id'];

        // File metadata
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];

        // File validation
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['pdf', 'doc', 'docx', 'zip'];

        if (in_array($fileExt, $allowedExtensions)) {
            if ($fileError === 0) {
                if ($fileSize < 25000000) { // Limit: 25MB

                    // Unique filename generation (avoid overwriting)
                    $newFileName = "student_" . $student_id . "_assign_" . $assignment_id . "_" . time() . "." . $fileExt;
                    $fileDestination = "uploads/" . $newFileName;

                    // Move file from temporary memory to uploads/ folder
                    if (move_uploaded_file($fileTmpName, $fileDestination)) {

                        // Save path in database
                        $sql = "INSERT INTO submissions (assignment_id, student_id, file_path) 
                                VALUES (:assignment_id, :student_id, :file_path)";

                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':assignment_id', $assignment_id);
                        $stmt->bindParam(':student_id', $student_id);
                        $stmt->bindParam(':file_path', $fileDestination);

                        if ($stmt->execute()) {
                            header("Location: course_detail.php?id=" . $course_id);
                            exit();
                        } else {
                            echo "Database entry failed!";
                        }
                    } else {
                        echo "Error moving uploaded file.";
                    }
                } else {
                    echo "Your file is too large (Limit is 5MB).";
                }
            } else {
                echo "Error uploading your file.";
            }
        } else {
            echo "Invalid file type. Only PDF, DOC, DOCX, ZIP allowed.";
        }
    }

    /* =========================
       ✅ ENROLL COURSE  (FIXED ✅)
    ==========================*/ elseif ($action == "enroll_course") {

        if ($_SESSION['role'] != 'student') {
            die("Access Denied");
        }

        $student_id = $_SESSION['user_id'];
        $course_id = $_POST['course_id'];

        try {
            $sql = "INSERT INTO enrollments (student_id, course_id)
                    VALUES (:student_id, :course_id)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->execute();
        } catch (PDOException $e) {
            // Already enrolled (ignore error)
        }

        header("Location: view_courses.php");
        exit();
    } else {
        echo "Invalid Action!";
    }
}
