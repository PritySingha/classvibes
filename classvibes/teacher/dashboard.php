<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
teacherOnly();

// Fetch teacher's classes
$teacher_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM classes WHERE teacher_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$classes = $stmt->get_result();

// Handle messages
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard - ClassVibes</title>
    <?php include '../includes/header.php'; ?>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <!-- Success/Error Messages -->
        <?php if($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <h3 class="mb-4">Your Classes</h3>
        <!-- New Class Form -->
        <div class="card dashboard-card mb-4">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-plus-circle me-2"></i>Start New Class</h5>
                <form action="start_class.php" method="POST">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <input type="text" name="class_name" 
                                   class="form-control" 
                                   placeholder="Enter class name" 
                                   required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-outline w-100">
                                <i class="fas fa-qrcode me-2"></i>Create Class
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Existing Classes -->
        <div class="row g-4">
            <?php while($class = $classes->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="card dashboard-card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($class['class_name']) ?></h5>
                            <p class="small mb-2">
                                Class ID: <?= $class['class_id'] ?>
                            </p>
                            <p class=" small">
                                Created: <?= date('M j, Y g:i a', strtotime($class['created_at'])) ?>
                            </p>
                            <div class="d-flex gap-2">
                                <a href="attendance_reports.php?class_id=<?= $class['class_id'] ?>" 
                                   class="btn btn-sm btn-outline">
                                    <i class="fas fa-chart-bar me-1"></i>View Attendance
                                </a>
                                <form method="POST" action="delete_class.php" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="class_id" value="<?= $class['class_id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this class? This action cannot be undone.");
    }
    </script>

</body>
</html>