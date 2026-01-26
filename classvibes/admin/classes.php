<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
adminOnly();

// Fetch classes with teacher info
$stmt = $conn->prepare("
    SELECT c.class_id, c.class_name, c.created_at, u.full_name AS teacher_name 
    FROM classes c
    LEFT JOIN users u ON c.teacher_id = u.id
    ORDER BY c.created_at DESC
");
$stmt->execute();
$classes = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Manage Classes</title>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">📚 Class Management</h2>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle custom-themed-table">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Teacher</th>
                                <th>Students</th>
                                <th>Sessions</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($class = $classes->fetch_assoc()): ?>
                            <?php
                                $classId = $class['class_id'];

                                // Secure Query for Student Count
                                $stmt = $conn->prepare("SELECT COUNT(*) FROM classes WHERE class_id = ?");
                                $stmt->bind_param("s", $classId);
                                $stmt->execute();
                                $studentCount = $stmt->get_result()->fetch_row()[0] ?? 0;

                                // Secure Query for Session Count
                                $stmt = $conn->prepare("SELECT COUNT(*) FROM attendance WHERE class_id = ?");
                                $stmt->bind_param("s", $classId);
                                $stmt->execute();
                                $sessionCount = $stmt->get_result()->fetch_row()[0] ?? 0;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($class['class_name']) ?></td>
                                <td><?= htmlspecialchars($class['teacher_name'] ?? 'Unassigned') ?></td>
                                <td><?= $studentCount ?></td>
                                <td><?= $sessionCount ?></td>
                                <td><?= format_date($class['created_at']) ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if ($classes->num_rows === 0): ?>
                                <tr><td colspan="5" class="text-center">No classes available.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
