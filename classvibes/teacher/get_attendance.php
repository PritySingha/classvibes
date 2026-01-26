<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
teacherOnly();

if (!isset($_GET['class_id'])) die("No class selected");

$class_id = $_GET['class_id'];
$teacher_id = $_SESSION['user_id'];

// Verify teacher owns the class
$stmt = $conn->prepare("SELECT class_id FROM classes WHERE class_id = ? AND teacher_id = ?");
$stmt->bind_param("si", $class_id, $teacher_id);
$stmt->execute();

if ($stmt->get_result()->num_rows === 0) {
    die("<div class='alert alert-danger'>Unauthorized access</div>");
}

// Get attendance records
$stmt = $conn->prepare("
    SELECT users.full_name, attendance.timestamp 
    FROM attendance 
    JOIN users ON attendance.student_id = users.id
    WHERE class_id = ?
    ORDER BY attendance.timestamp DESC
");
$stmt->bind_param("s", $class_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '
    <div class="card dashboard-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover attendance-table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Attendance Time</th>
                        </tr>
                    </thead>
                    <tbody>';

    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['full_name']}</td>
                <td>".date('M j, Y g:i a', strtotime($row['timestamp']))."</td>
              </tr>";
    }

    echo '      </tbody>
                </table>
            </div>
        </div>
    </div>';
} else {
    echo '<div class="alert alert-info">No attendance records found for this class</div>';
}
?>