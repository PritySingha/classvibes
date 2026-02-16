<?php
session_start();

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }
}

function adminOnly() {
    redirectIfNotLoggedIn();
    if ($_SESSION['role'] !== 'admin') {
        header("Location: ../auth/login.php");
        exit();
    }
}

function teacherOnly() {
    redirectIfNotLoggedIn();
    if ($_SESSION['role'] !== 'teacher') {
        header("Location: ../auth/login.php");
        exit();
    }
}

function studentOnly() {
    redirectIfNotLoggedIn();
    if ($_SESSION['role'] !== 'student') {
        header("Location: ../auth/login.php");
        exit();
    }
}
?>