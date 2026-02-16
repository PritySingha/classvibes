<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
adminOnly();

// Get current settings
$settings = $conn->query("SELECT * FROM settings")->fetch_all(MYSQLI_ASSOC);

// Update settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['settings'] as $key => $value) {
        $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = ?");
        $stmt->bind_param('ss', $value, $key);
        $stmt->execute();
    }
    $_SESSION['success'] = "Settings updated successfully";
    header("Refresh:0");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include '../includes/header.php'; ?>
    <title>System Settings</title>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>System Settings</h2>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3">
                        <?php foreach ($settings as $setting): ?>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?= ucwords(str_replace('_', ' ', $setting['name'])) ?></label>
                                <?php if($setting['type'] === 'boolean'): ?>
                                    <select name="settings[<?= $setting['name'] ?>]" class="form-select">
                                        <option value="1" <?= $setting['value'] ? 'selected' : '' ?>>Enabled</option>
                                        <option value="0" <?= !$setting['value'] ? 'selected' : '' ?>>Disabled</option>
                                    </select>
                                <?php else: ?>
                                    <input type="<?= $setting['type'] ?>" 
                                           name="settings[<?= $setting['name'] ?>]" 
                                           class="form-control"
                                           value="<?= htmlspecialchars($setting['value']) ?>">
                                <?php endif; ?>
                                <small class="text-muted"><?= $setting['description'] ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-outline">Save Settings</button>
                </form>
                
                <hr class="my-4">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <h5>Database Backup</h5>
                        <a href="../actions/backup_db.php" class="btn btn-outline-secondary">
                            <i class="fas fa-database"></i> Download Backup
                        </a>
                    </div>
                    <div class="col-md-6">
                        <h5>System Logs</h5>
                        <a href="../logs/" class="btn btn-outline-secondary">
                            <i class="fas fa-file-alt"></i> View Logs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>