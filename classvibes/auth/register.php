<?php
$page_title = 'Register';
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $conn->real_escape_string($_POST['role']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Hash the password before saving
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email exists
        $result = $conn->query("SELECT id FROM users WHERE email = '$email'");
        
        if ($result->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            $sql = "INSERT INTO users (full_name, email, password, role) 
                    VALUES ('$full_name', '$email', '$hashed_password', '$role')";
            
            if ($conn->query($sql)) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - ClassVibes</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <script defer>
        // Real-time password matching validation
        document.addEventListener("DOMContentLoaded", function() {
            var password = document.getElementById('password');
            var confirmPassword = document.getElementById('confirm_password');
            var errorText = document.getElementById('passwordError');

            function validatePasswords() {
                if (confirmPassword.value.length > 0) { 
                    if (password.value !== confirmPassword.value) {
                        errorText.style.display = "block";
                        errorText.textContent = "Passwords do not match!";
                    } else {
                        errorText.style.display = "none";
                    }
                } else {
                    errorText.style.display = "none";
                }
            }

            password.addEventListener("input", validatePasswords);
            confirmPassword.addEventListener("input", validatePasswords);
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="auth-card card">
            <div class="card-body">
                <div class="registration-header text-center mb-4">
                    <i class="fas fa-user-plus fa-3x mb-3"></i>
                    <h2 class="h3">Create Your Account</h2>
                </div>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <!-- Full Name Input -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="full_name" class="form-control form-control-lg" placeholder="Enter your full name" required>
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email address" required>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-4 position-relative">
                        <label class="form-label fw-medium">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Enter your password" required>
                            <span class="password-toggle position-absolute" id="togglePassword" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="mb-4 position-relative">
                        <label class="form-label fw-medium">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-lg" placeholder="Enter your password to confirm" required>
                            <span class="password-toggle position-absolute" id="toggleConfirmPassword" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <small id="passwordError" class="text-danger" style="display: none;">Passwords do not match</small>
                    </div>
                    <!-- Role Selection -->
                    <div class="mb-4 role-selector">
                        <label class="form-label fw-medium">I am a</label>
                        <select name="role" class="form-select form-select-lg" required>
                            <option value="admin" class="text-danger">admin</option>
                            <option value="teacher" class="text-primary">Teacher</option>
                            <option value="student" class="text-success">Student</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-light btn-lg w-100 py-2">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <p class="mb-0">Already have an account? 
                        <a href="login.php">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
