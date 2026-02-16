<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
studentOnly();

$student_id = $_SESSION['user_id'];

// Get attendance records
$stmt = $conn->prepare("
    SELECT classes.class_name, attendance.timestamp 
    FROM attendance 
    JOIN classes ON attendance.class_id = classes.class_id
    WHERE student_id = ?
    ORDER BY attendance.timestamp DESC
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$attendance = $stmt->get_result();

// Get total attended classes
$total_stmt = $conn->prepare("
    SELECT COUNT(*) AS total 
    FROM attendance 
    WHERE student_id = ?
");
$total_stmt->bind_param("i", $student_id);
$total_stmt->execute();
$total = $total_stmt->get_result()->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard - ClassVibes</title>
    <?php include '../includes/header.php'; ?>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <!-- Dashboard Summary -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card dashboard-card student-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-user-graduate me-2"></i>
                            Welcome, <?= isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : 'Student' ?>
                        </h5>
                        <p class="mb-0">
                            Student ID: <span class="text-muted"><?= $_SESSION['user_id'] ?></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card dashboard-card student-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-calendar-check me-2"></i>
                            Attendance Summary
                        </h5>
                        <p class="display-6 mb-0"><?= $total ?> Classes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance History -->
        <div class="card dashboard-card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fas fa-history me-2"></i>
                    Attendance History
                </h5>
                
                <?php if($attendance->num_rows > 0): ?>
                    <div class="list-group">
                        <?php while($record = $attendance->fetch_assoc()): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1"><?= $record['class_name'] ?></h6>
                                        <small class="text-muted">
                                            <?= date('M j, Y g:i a', strtotime($record['timestamp'])) ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-success bg-opacity-50 rounded-pill">
                                        <i class="fas fa-check " ></i>
                                    </span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        No attendance records found. Attend a class to get started!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <a href="scan.php" class="btn btn-outline btn-lg floating-btn">
        <i class="fas fa-qrcode"></i>
    </a>

    <?php include '../includes/footer.php'; ?>
</body>
</html>