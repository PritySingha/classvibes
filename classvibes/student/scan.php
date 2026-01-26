<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
studentOnly();

$student_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'] ?? null;
    
    // Validate class exists and is still active
    $class_stmt = $conn->prepare("SELECT class_id, qr_expires_at FROM classes WHERE class_id = ?");
    $class_stmt->bind_param("s", $class_id);
    $class_stmt->execute();
    $class_result = $class_stmt->get_result();

    if (!$class_result->num_rows) {
        die("Invalid class code");
    }

    $class = $class_result->fetch_assoc();
    if (strtotime($class['qr_expires_at']) < time()) {
        die("QR Code has expired");
    }

    $class_stmt->bind_param("s", $class_id);
    $class_stmt->execute();
    
    if (!$class_stmt->get_result()->num_rows) {
        die("Invalid class code");
    }

    // Check for existing attendance today
    $existing_stmt = $conn->prepare("
        SELECT id FROM attendance 
        WHERE student_id = ? 
        AND class_id = ?
        AND DATE(timestamp) = CURDATE()
    ");
    $existing_stmt->bind_param("is", $student_id, $class_id);
    $existing_stmt->execute();
    
    if ($existing_stmt->get_result()->num_rows > 0) {
        die("Attendance already marked today");
    }

    // Record attendance
    $insert_stmt = $conn->prepare("
        INSERT INTO attendance (student_id, class_id, timestamp)
        VALUES (?, ?, NOW())
    ");
    $insert_stmt->bind_param("is", $student_id, $class_id);
    
    if ($insert_stmt->execute()) {
        header("Location: dashboard.php?success=Attendance recorded");
        exit();
    } else {
        header("Location: scan.php?error=Database error");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Scan QR Code - ClassVibes</title>
    <?php include '../includes/header.php'; ?>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-4">
                    <i class="fas fa-qrcode me-2"></i>
                    Scan Class QR Code
                </h3>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>
                
                <div class="scan-area text-center">
                    <video id="preview" class="rounded border" style="max-width: 500px; width: 100%"></video>
                    <div class="scan-overlay"></div>
                </div>

                <form id="attendanceForm" method="POST" style="display: none;">
                    <input type="hidden" name="class_id" id="classIdInput">
                </form>
            </div>
        </div>
    </div>

    <script>
    let scanner = null;
    
    window.addEventListener('load', () => {
        Instascan.Camera.getCameras().then(cameras => {
            if (cameras.length > 0) {
                scanner = new Instascan.Scanner({
                    video: document.getElementById('preview'),
                    mirror: false
                });
                
                scanner.addListener('scan', content => {
                    document.getElementById('classIdInput').value = content;
                    document.getElementById('attendanceForm').submit();
                });
                
                scanner.start(cameras[0]);
            } else {
                document.querySelector('.scan-area').innerHTML = `
                    <div class="alert alert-danger">
                        No cameras found. Please enable camera access.
                    </div>
                `;
            }
        }).catch(error => {
            console.error(error);
            document.querySelector('.scan-area').innerHTML = `
                <div class="alert alert-danger">
                    Camera access denied. Please enable camera permissions.
                </div>
            `;
        });
    });
    </script>

    <?php include '../includes/footer.php'; ?>
</body>
</html>