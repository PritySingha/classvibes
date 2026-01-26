<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
adminOnly();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID.");
}

$id = (int)$_GET['id']; 
$user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();

if (!$user) {
    die("User not found.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $role  = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET full_name=?, email=?, password=?, role=? WHERE id=?");
        $update->bind_param("ssssi", $name, $email, $password, $role, $id);
    } else {
        $update = $conn->prepare("UPDATE users SET full_name=?, email=?, role=? WHERE id=?");
        $update->bind_param("sssi", $name, $email, $role, $id);
    }

    if ($update->execute()) {
        header('Location: ../admin/users.php');
        exit;
    } else {
        die("Update failed: " . $conn->error);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Edit User</title>
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">
    <div class="card shadow rounded p-4">
        <h3 class="mb-4">Edit User</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small>:</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Role:</label>
                <select name="role" class="form-select">
                    <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                    <option value="teacher" <?= $user['role'] === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update User</button>
            <a href="../admin/users.php" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>

