<?php
require_once 'includes/auth.php';

// Redirect logged-in users to their dashboard
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    }
    else if ($_SESSION['role'] === 'teacher') {
        header("Location: teacher/dashboard.php");
    } 
    else {
        header("Location: student/dashboard.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/header.php'; ?>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <!-- Hero Section -->
    <div class="hero-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="fas fa-chalkboard-teacher"></i> Welcome to ClassVibes
                    </h1>
                    <p class="lead mb-4">
                        Transform your classroom experience with modern attendance tracking 
                        and management solutions for teachers and students.
                    </p>
                    <div class="d-grid gap-2 d-md-block">
                        <a href="auth/login.php" class="btn btn-light btn-lg me-2">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="auth/register.php" class="btn btn-outline btn-lg">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center d-none d-lg-block">
                    <img src="assets/images/ClassVibes_logo_svg.png" alt="Classroom" class="img-fluid" style="max-height: 600px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Admin Features -->
            <div class="col-md-4">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <h3 class="card-title mb-4">
                            <i class="fas fa-user-shield me-2"></i> For Admin
                        </h3>
                        <ul class="list-group list-unstyled">
                            <li class="">
                                <i class="fas fa-users-cog text-success me-2"></i>
                                Manage users seamlessly
                            </li>
                            <li class="">
                                <i class="fas fa-chart-line text-info me-2"></i>
                                Generate detailed reports
                            </li>
                            <li class="">
                                <i class="fas fa-tools text-warning me-2"></i>
                                Customize system settings
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
                <!-- Teacher Features -->
                <div class="col-md-4">
                    <div class="card h-100 shadow">
                        <div class="card-body">
                            <h3 class="card-title mb-4 ">
                                <i class="fas fa-chalkboard-teacher me-2"></i>For Teachers
                            </h3>
                            <ul class="list-group list-unstyled">
                                <li class="">
                                    <i class="fas fa-qrcode text-success me-2"></i>
                                    Generate class QR codes instantly
                                </li>
                                <li class="">
                                    <i class="fas fa-chart-bar text-info me-2"></i>
                                    Real-time attendance tracking
                                </li>
                                <li class="">
                                    <i class="fas fa-file-export text-warning me-2"></i>
                                    Detailed attendance reports
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Student Features -->
                <div class="col-md-4">
                    <div class="card h-100 shadow">
                        <div class="card-body">
                            <h3 class="card-title mb-4">
                                <i class="fas fa-user-graduate me-2"></i>For Students
                            </h3>
                            <ul class="list-group list-unstyled">
                                <li class="">
                                    <i class="fas fa-mobile-alt text-success me-2"></i>
                                    Quick QR code scanning
                                </li>
                                <li class="">
                                    <i class="fas fa-history text-info me-2"></i>
                                    Attendance history tracking
                                </li>
                                <li class="">
                                    <i class="fas fa-bell text-warning me-2"></i>
                                    Real-time notifications
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class=" py-5">
        <div class="container text-center">
            <h2 class="mb-4">Ready to Get Started?</h2>
            <p class="lead mb-4">Join hundreds of educators and students already using ClassVibes</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="auth/register.php" class="btn btn-light btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Create Account
                </a>
                <a href="auth/login.php" class="btn btn-outline btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Existing User
                </a>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
