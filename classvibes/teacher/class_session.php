<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
teacherOnly();

if (!isset($_SESSION['current_class'])) {
    header("Location: dashboard.php?error=" . urlencode("No active class session."));
    exit();
}

// Fetch current class details
$class_id = $_SESSION['current_class'];
$stmt = $conn->prepare("SELECT class_id, class_name, created_at, qr_expires_at FROM classes WHERE class_id = ?");
$stmt->bind_param("s", $class_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: dashboard.php?error=" . urlencode("Class not found."));
    exit();
}

$class = $result->fetch_assoc();
date_default_timezone_set('UTC'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
            <div class="d-flex justify-content-center align-items-center mb-4">
                <div id="qrcode"></div>
            </div>

            <!-- Class Info -->
            <div class="alert alert-info">
                <p class="mb-1">Class ID: <strong><?= htmlspecialchars($class['class_id']) ?></strong></p>
                <p class="mb-0">Started: <?= date('M j, Y g:i a', strtotime($class['created_at'])) ?></p>
            </div>

            <!-- Timer Display -->
            <div class="alert alert-warning mt-3">
                <i class="fas fa-clock me-2"></i>
                <span id="timer">QR Code valid for 10:00</span>
            </div>

            <a href="dashboard.php" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
</div>

<script>
// Generate QR Code with class session info
const classId = "<?= htmlspecialchars($class['class_id']) ?>";

new QRCode(document.getElementById("qrcode"), {
    text: classId,
    width: 200,
    height: 200,
    colorDark: "#181818",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

// Countdown Timer
const expirationTime = new Date("<?= date('c', strtotime($class['qr_expires_at'])) ?>").getTime();

function updateTimer() {
    const now = new Date().getTime();
    const remaining = expirationTime - now;

    if (remaining <= 0) {
        document.getElementById('timer').textContent = "QR Code expired";
        document.getElementById('qrcode').innerHTML = '';
        return;
    }

    const minutes = Math.floor(remaining / (1000 * 60));
    const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
    document.getElementById('timer').textContent =
        `QR Code valid for ${minutes}:${seconds.toString().padStart(2, '0')}`;

    setTimeout(updateTimer, 1000);
}

updateTimer();

</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
