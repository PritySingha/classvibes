<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
adminOnly();

// Get statistics
$stats = [
    'students' => $conn->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetch_row()[0],
    'teachers' => $conn->query("SELECT COUNT(*) FROM users WHERE role='teacher'")->fetch_row()[0],
    'admins' => $conn->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetch_row()[0],
    'classes' => $conn->query("SELECT COUNT(*) FROM classes")->fetch_row()[0],
    'attendance' => $conn->query("SELECT COUNT(*) FROM attendance")->fetch_row()[0]
];
?>
<!DOCTYPE html>
<html>
<head>
    <?php include '../includes/header.php'; ?>
    <title>Admin Dashboard</title>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-4">
        <h2 class="mb-4">Admin Dashboard</h2>

        <!-- Statistic Cards -->
        <div class="row row-cols-1 row-cols-md-5 g-4 mb-4">
            <?php foreach ($stats as $key => $value): ?>
                <div class="col">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-capitalize"><?= $key ?></h5>
                            <p class="card-text display-5"><?= $value ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Admin Feature Cards -->
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">User Management</h5>
                        <p class="card-text">Add new users, edit existing users, assign roles, and manage user access.</p>
                        <a href=../admin/users.php class="btn ">Manage Users</a>
                        <a href="../actions/add_user.php" class="btn ">Add New User</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Class Management</h5>
                        <p class="card-text">Create and manage classes, assign teachers, and set schedules.</p>
                        <a href="classes.php" class="btn">Manage Classes</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Attendance Reports</h5>
                        <p class="card-text">View class attendance records, export data, and monitor student attendance.</p>
                        <a href="reports.php" class="btn">View Reports</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">System Settings</h5>
                        <p class="card-text">Update site title, contact info, app configurations, and system behavior.</p>
                        <a href="settings.php" class="btn ">System Settings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
