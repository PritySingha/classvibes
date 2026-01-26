<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
studentOnly();

$student_id = $_SESSION['user_id'];

// Get student's attended classes
$stmt = $conn->prepare("
    SELECT c.class_id, c.class_name, c.created_at, u.full_name AS teacher_name
    FROM classes c
    JOIN attendance a ON c.class_id = a.class_id
    JOIN users u ON c.teacher_id = u.id
    WHERE a.student_id = ?
    GROUP BY c.class_id
    ORDER BY c.created_at DESC
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$classes = $stmt->get_result();

// Total distinct classes attended
$total_stmt = $conn->prepare("
    SELECT COUNT(DISTINCT class_id) AS total 
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
                <div class="card dashboard-card teacher-card"> 
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-user-graduate me-2"></i> 
                            Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?>
                        </h5>
                        <p class="mb-0">
                            Enrolled In: <span class=""><?= $total ?> Classes</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card dashboard-card teacher-card"> 
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-book-open me-2"></i> 
                            Teaching Summary
                        </h5>
                        <p class="display-6 mb-0"><?= $total ?> Classes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Management -->
        <div class="card dashboard-card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fas fa-chalkboard me-2"></i>
                    Your Classes
                </h5>
                
                <?php if($classes->num_rows > 0): ?>
                    <div class="list-group">
                        <?php while($class = $classes->fetch_assoc()): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= $class['class_name'] ?></h6>
                                        <small class="">
                                            Created: <?= date('M j, Y', strtotime($class['created_at'])) ?>
                                        </small>
                                    </div>
                                    
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        No classes found. Start your first class session!
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