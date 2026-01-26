<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
teacherOnly();

// Verify POST request and required parameters
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['class_id'])) {
    header("Location: dashboard.php?error=Invalid request");
    exit();
}

$teacher_id = $_SESSION['user_id'];
$class_id = $_POST['class_id'];

// Delete related attendance records
$stmt = $conn->prepare("DELETE FROM attendance WHERE class_id = ?");
$stmt->bind_param("s", $class_id);
$stmt->execute();
// Verify class ownership before deletion
$stmt = $conn->prepare("DELETE FROM classes 
                      WHERE class_id = ? 
                      AND teacher_id = ?");
$stmt->bind_param("si", $class_id, $teacher_id);

if ($stmt->execute()) {
    // Check if any rows were affected
    if ($stmt->affected_rows > 0) {
        header("Location: dashboard.php?success=Class deleted successfully");
    } else {
        header("Location: dashboard.php?error=Class not found or you don't have permission");
    }
} else {
    header("Location: dashboard.php?error=Error deleting class");
}

exit();
?>