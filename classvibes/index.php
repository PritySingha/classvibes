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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "classvibes@gmail.com";
    $subject = "New Contact Message";

    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    $body = "You have received a new message:\n\n";
    $body .= "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Message:\n$message\n";

    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo '<div class="alert alert-success text-center">Message sent successfully!</div>';
    } else {
        echo '<div class="alert alert-danger text-center">Message sending failed. Please try again.</div>';
    }
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
    <div class="hero-section vh-100" id="home">
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
                    <img src="assets/images/ClassVibes_logo_svg.png" alt="Classroom" class="img-fluid" style="max-height: 900px; width: 100%;">
                </div>
            </div>
        </div>
    </div>

    <!-- Services/Features Section -->
    <div class="py-5" id="services">
        <div class="container text-center">
            <h2 class="mb-4">Our Services</h2>
            <p class="lead mb-4">We offer powerful tools for Admins, Teachers, and Students.</p>
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
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
<section class="py-5" id="about">
    <div class="container">
        <div class="row align-items-center">
            <!-- Image Column -->
            <div class="col-lg-5 mb-4 mb-lg-0">
                <img class="img-fluid rounded" src="./assets/images/about-img-1.jpg" alt="About Us">
            </div>
            <!-- Text Column -->
            <div class="col-lg-7">
    <h2 class="mb-4">About Us</h2>
    <p class="lead text-secondary mb-3">
        ClassVibes modernizes attendance tracking through innovative QR technology, enhancing educational efficiency and student engagement.
    </p>
    <p class="mb-4">
        Our platform empowers educators with real-time insights while providing students with a seamless attendance experience. Designed for modern educational institutions prioritizing both security and convenience.
    </p>
    <div class="row">
        <!-- Feature 1 -->
        <div class="col-md-6 d-flex mb-3">
            <div class="me-3 text-primary">
                <i class="fas fa-clock fa-2x"></i>
            </div>
            <div>
                <h5 class="mb-1">Real-Time Tracking</h5>
                <p class="text-secondary mb-0">Instant attendance updates with live classroom monitoring and analytics.</p>
            </div>
        </div>
        <!-- Feature 2 -->
        <div class="col-md-6 d-flex mb-3">
            <div class="me-3 text-primary">
                <i class="fas fa-qrcode fa-2x"></i>
            </div>
            <div>
                <h5 class="mb-1">Secure QR System</h5>
                <p class="text-secondary mb-0">Time-limited encrypted codes prevent unauthorized attendance marking.</p>
            </div>
        </div>
        <!-- Feature 3 -->
        <div class="col-md-6 d-flex mb-3">
            <div class="me-3 text-primary">
                <i class="fas fa-chart-line fa-2x"></i>
            </div>
            <div>
                <h5 class="mb-1">Data-Driven Insights</h5>
                <p class="text-secondary mb-0">Comprehensive reports to track student participation patterns.</p>
            </div>
        </div>
        <!-- Feature 4 -->
        <div class="col-md-6 d-flex mb-3">
            <div class="me-3 text-primary">
                <i class="fas fa-mobile-alt fa-2x"></i>
            </div>
            <div>
                <h5 class="mb-1">Mobile-First Design</h5>
                <p class="text-secondary mb-0">Optimized experience for both instructors and students on any device.</p>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</section>


    <!-- Call to Action Section -->
    <div class=" py-5">
        <div class="container text-center">
            <h2 class="mb-4">Ready to Get Started?</h2>
            <p class="lead mb-4">Join other educators and students already using ClassVibes</p>
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

    <!-- Contact Section -->
<div class="py-5" id="contact">
    <div class="container">
        <div class="row">
            <!-- Contact Info -->
            <div class="col-md-6">
                <h2 class="mb-4">Contact Us</h2>
                <p>We'd love to hear from you. Reach out to us using the details below or send a message.</p>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i> 
                        Raniganj, WB, India
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone text-success me-2"></i> 
                        +91 99999 99999
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-envelope text-danger me-2"></i> 
                        classvibes@gmail.com
                    </li>
                </ul>
            </div>
            <!-- Contact Form -->
            <div class="col-md-6">
        <form method="POST" action="">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Your Email" required>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" class="form-control" id="message" rows="4" placeholder="Your Message" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane me-1"></i> Send Message
          </button>
        </form>
      </div>
        </div>
    </div>
</div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
