<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

teacherOnly(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_name = trim($_POST['class_name'] ?? '');
    
    if ($class_name === '') {
        header("Location: dashboard.php?error=" . urlencode("Class name is required."));
        exit();
    }

    // Generate unique class ID and QR expiration time
    $class_id = substr(md5(uniqid('', true)), 0, 8);
    $teacher_id = $_SESSION['user_id'];
    $qr_expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    try {
        // Prepare statement to insert new class
        $stmt = $conn->prepare(
            "INSERT INTO classes (class_id, teacher_id, class_name, qr_expires_at) 
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("siss", $class_id, $teacher_id, $class_name, $qr_expires_at);

        // Execute and redirect
        if ($stmt->execute()) {
            $_SESSION['current_class'] = $class_id;
            header("Location: class_session.php");
            exit();
        } else {
            error_log("Database insert failed: " . $stmt->error);
            header("Location: dashboard.php?error=" . urlencode("Class creation failed."));
            exit();
        }

    } catch (Exception $e) {
        error_log("Class creation error: " . $e->getMessage());
        header("Location: dashboard.php?error=" . urlencode("Unexpected error occurred."));
        exit();
    }
}

header("Location: dashboard.php");
exit();
