<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
teacherOnly();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate unique class ID
    $class_id = substr(md5(uniqid()), 0, 8);
    $class_name = $conn->real_escape_string($_POST['class_name']);
    $teacher_id = $_SESSION['user_id'];

    try {
        $stmt = $conn->prepare("INSERT INTO classes (class_id, teacher_id, class_name) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $class_id, $teacher_id, $class_name);
        
        if ($stmt->execute()) {
            $_SESSION['current_class'] = $class_id;
            header("Location: class_session.php");
            exit();
        } else {
            header("Location: dashboard.php?error=Class creation failed");
            exit();
        }
    } catch (Exception $e) {
        error_log("Class creation error: " . $e->getMessage());
        header("Location: dashboard.php?error=Database error");
        exit();
    }
}

header("Location: dashboard.php");