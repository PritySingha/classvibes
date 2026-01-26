<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
adminOnly();

// Fetch all users
$result = $conn->query("SELECT id, full_name, email, role FROM users ORDER BY role, full_name");
?>
<!DOCTYPE html>
<html>
<head>
    <?php include '../includes/header.php'; ?>
    <title>Manage Users</title>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Manage Users</h2>
        
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td class="text-capitalize"><?= $user['role'] ?></td>
                            <td>
                                <a href="../actions/edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="../actions/delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <a href="../actions/add_user.php" class="btn btn-success mt-3">Add New User</a>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
