<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
studentOnly();  

// Fetch logged-in student info
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, full_name, email, avatar, created_at, password FROM users WHERE id = ? AND role = 'student'");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Not found or not a student
    header("Location: ../logout.php");
    exit();
}

$user = $result->fetch_assoc();

$errors = [];
$success = null;

// Handle avatar update only
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['change_password'])) {
    $existing_avatar = $_POST['existing_avatar'] ?? $user['avatar'];

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExt, $allowedExts)) {
            $errors[] = "Only JPG, JPEG, PNG, GIF files are allowed for avatar.";
        } else {
            $newFileName = 'avatar_' . $userId . '.' . $fileExt;
            $uploadDir = '../assets/uploads/avatars/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destPath = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                $errors[] = "Failed to upload avatar image.";
            } else {
                $existing_avatar = $newFileName;

                // Update avatar in DB
                $stmtUpdate = $conn->prepare("UPDATE users SET avatar = ? WHERE id = ? AND role = 'student'");
                $stmtUpdate->bind_param("si", $existing_avatar, $userId);
                if ($stmtUpdate->execute()) {
                    $success = "Avatar updated successfully.";
                    $user['avatar'] = $existing_avatar;
                } else {
                    $errors[] = "Failed to update avatar in database.";
                }
            }
        }
    } else {
        $errors[] = "Please choose an avatar file to upload.";
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $errors[] = "All password fields are required.";
    } elseif (!password_verify($current_password, $user['password'])) {
        $errors[] = "Current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "New passwords do not match.";
    } else {
        $newPasswordHash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmtPass = $conn->prepare("UPDATE users SET password = ? WHERE id = ? AND role = 'student'");
        $stmtPass->bind_param("si", $newPasswordHash, $userId);
        if ($stmtPass->execute()) {
            $success = "Password changed successfully.";
        } else {
            $errors[] = "Failed to update password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Student Profile - ClassVibes</title>
    <?php include '../includes/header.php'; ?>
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <div class="row gutters">
        <!-- Profile Summary -->
        <div class="col-xl-3 col-lg-4 col-md-12">
            <div class="card h-100">
                <div class="card-body account-settings text-center">
                    <img src="../assets/uploads/avatars/<?= htmlspecialchars($user['avatar'] ?? 'default.png') ?>"
                         class="rounded-circle border mb-3" width="140" height="140" alt="Avatar">

                    <h5 class="mt-3"><?= htmlspecialchars($user['full_name']) ?></h5>
                    <h6 class=""><?= htmlspecialchars($user['email']) ?></h6>
                    <p class=" mt-2">
                        <i class="fas fa-user-graduate me-2"></i>
                        Joined: <?= date('M Y', strtotime($user['created_at'])) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Edit Section -->
        <div class="col-xl-9 col-lg-8 col-md-12">
            <div class="card h-100">
                <div class="card-body">

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Avatar Upload Form -->
                    <form method="post" enctype="multipart/form-data" novalidate>
                        <input type="hidden" name="existing_avatar" value="<?= htmlspecialchars($user['avatar']) ?>">

                        <div class="row gutters">
                            <div class="col-12">
                                <h6 class="mb-3 text-primary">Student Details</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fullName">Full Name</label>
                                <input type="text" class="form-control" id="fullName"
                                       value="<?= htmlspecialchars($user['full_name']) ?>" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email"
                                       value="<?= htmlspecialchars($user['email']) ?>" readonly>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="avatar" class="form-label">Change Avatar</label>
                                <input type="file" name="avatar" class="form-control" id="avatar" accept="image/*">
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Avatar</button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <!-- Password Change Form -->
                    <h6 class="mb-3 text-primary">Change Password</h6>

                    <form method="post" novalidate>
                        <input type="hidden" name="change_password" value="1">

                        <div class="mb-3">
                            <label for="currentPassword">Current Password</label>
                            <input type="password" name="current_password" class="form-control" id="currentPassword" required>
                        </div>

                        <div class="mb-3">
                            <label for="newPassword">New Password</label>
                            <input type="password" name="new_password" class="form-control" id="newPassword" required>
                        </div>

                        <div class="mb-3">
                            <label for="confirmPassword">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" id="confirmPassword" required>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">Change Password</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
