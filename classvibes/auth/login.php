<?php
$page_title = 'Login';
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            
            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php"); 
            } elseif ($user['role'] === 'teacher') {
                header("Location: ../teacher/dashboard.php");
            } else {
                header("Location: ../student/dashboard.php");
            }            
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - ClassVibes</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="auth-card card">
            <div class="card-body">
                <div class="login-header text-center mb-4">
                    <i class="fas fa-sign-in-alt fa-3x mb-3"></i>
                    <h2 class="h3">Welcome Back to ClassVibes</h2>
                </div>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" 
                                class="form-control" 
                                placeholder="Enter your email address" 
                                required>
                        </div>
                    </div>

                    <div class="mb-4 position-relative">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                            <span class="password-toggle position-absolute" id="togglePassword" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </div>

                    <div class="text-center">
                        <p>Don't have an account?
                            <a href="../auth/register.php" >Create one</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
    <?php require_once '../includes/footer.php'; ?>
