<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
teacherOnly();

if (!isset($_SESSION['current_class'])) {
    header("Location: dashboard.php");
    exit();
}

// Get class details
$stmt = $conn->prepare("SELECT * FROM classes WHERE class_id = ?");
$stmt->bind_param("s", $_SESSION['current_class']);
$stmt->execute();
$class = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Class Session - ClassVibes</title>
    <?php include '../includes/header.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="card dashboard-card mx-auto" style="max-width: 600px;">
            <div class="card-body text-center">
                <h3 class="card-title mb-4">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    <?= htmlspecialchars($class['class_name']) ?>
                </h3>
                
                <!-- QR Code Container -->
                <div class="container d-flex justify-content-center align-items-center ">
                    <div id="qrcode" class="mb-4"></div>
                </div>                
                
                <!-- Class Info -->
                <div class="alert alert-info">
                    <p class="mb-1">Class ID: <strong><?= $class['class_id'] ?></strong></p>
                    <p class="mb-0">Started: <?= date('M j, Y g:i a', strtotime($class['created_at'])) ?></p>
                </div>

                <a href="dashboard.php" class="btn">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <script>
    // Generate QR Code
    new QRCode(document.getElementById("qrcode"), {
        text: "<?= $class['class_id'] ?>",
        width: 200,
        height: 200,
        colorDark: "#181818",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
    </script>

    <?php include '../includes/footer.php'; ?>
</body>
</html>