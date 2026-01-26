<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
adminOnly();

// User growth data
$user_growth = $conn->query("
    SELECT DATE(created_at) AS date, COUNT(*) AS count 
    FROM users 
    GROUP BY DATE(created_at) 
    ORDER BY date
");

// Attendance data
$attendance_data = $conn->query("
    SELECT c.class_name AS class_name, COUNT(a.id) AS attendance_count
    FROM attendance a
    JOIN classes c ON a.class_id = c.class_id
    GROUP BY c.class_id
    ORDER BY attendance_count DESC
    LIMIT 10;
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Dashboard Reports</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="page-title">📊 System Reports</h2>

        <div class="row g-4">
            <!-- User Growth Chart -->
            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="mb-3 text-primary">📈 User Growth Over Time</h5>
                    <canvas id="userGrowthChart" height="200"></canvas>
                </div>
            </div>

            <!-- Attendance Chart -->
            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="mb-3 text-success">🏫 Top Classes by Attendance</h5>
                    <canvas id="attendanceChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        // Prepare PHP data
        const userGrowthLabels = [<?php $user_growth->data_seek(0); while($row = $user_growth->fetch_assoc()) echo "'".$row['date']."',"; ?>];
        const userGrowthData = [<?php $user_growth->data_seek(0); while($row = $user_growth->fetch_assoc()) echo $row['count'].','; ?>];

        const attendanceLabels = [<?php $attendance_data->data_seek(0); while($row = $attendance_data->fetch_assoc()) echo "'".$row['class_name']."',"; ?>];
        const attendanceCounts = [<?php $attendance_data->data_seek(0); while($row = $attendance_data->fetch_assoc()) echo $row['attendance_count'].','; ?>];

        // User Growth Chart
        new Chart(document.getElementById('userGrowthChart'), {
            type: 'line',
            data: {
                labels: userGrowthLabels,
                datasets: [{
                    label: 'Users Registered',
                    data: userGrowthData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Attendance Chart
        new Chart(document.getElementById('attendanceChart'), {
            type: 'bar',
            data: {
                labels: attendanceLabels,
                datasets: [{
                    label: 'Attendance Count',
                    data: attendanceCounts,
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
