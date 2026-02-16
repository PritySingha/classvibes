<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
adminOnly();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $stmt->execute();

    header('Location: ../admin/users.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include '../includes/header.php'; ?>
    <title>Add User</title>
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <h2>Add New User</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Role:</label>
            <select name="role" class="form-select">
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Add User</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
