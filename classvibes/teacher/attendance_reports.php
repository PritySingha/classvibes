<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
teacherOnly();

// Get selected class ID
$class_id = $_GET['class_id'] ?? null;

// Fetch teacher's classes
$teacher_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT class_id, class_name FROM classes WHERE teacher_id = ?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$classes = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance Reports - ClassVibes</title>
    <?php include '../includes/header.php'; ?>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <h3 class="mb-4">Attendance Reports</h3>
        
        <!-- Class Selector -->
        <div class="card dashboard-card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label">Select Class:</label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-select" id="classSelector">
                            <option value="">Choose a class...</option>
                            <?php while($class = $classes->fetch_assoc()): ?>
                                <option value="<?= $class['class_id'] ?>" 
                                    <?= $class['class_id'] === $class_id ? 'selected' : '' ?>>
                                    <?= $class['class_name'] ?> (<?= $class['class_id'] ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div id="attendanceResults"></div>
    </div>

    <script>
    // Load attendance when class selected
    document.getElementById('classSelector').addEventListener('change', function() {
        const classId = this.value;
        if(!classId) return;

        // Show loading state
        document.getElementById('attendanceResults').innerHTML = `
            <div class="text-center my-5">
                <div class="spinner-border " role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

        // Fetch attendance data
        fetch(`get_attendance.php?class_id=${classId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('attendanceResults').innerHTML = data;
            });
    });

    // Load initial data if class selected
    <?php if($class_id): ?>
        document.getElementById('classSelector').dispatchEvent(new Event('change'));
    <?php endif; ?>
    </script>

    <?php include '../includes/footer.php'; ?>
</body>
</html>