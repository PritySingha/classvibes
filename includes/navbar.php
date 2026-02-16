<nav class="navbar sticky-top navbar-expand-lg shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
            <i class="fas fa-chalkboard-teacher"></i>
            ClassVibes
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <!-- For Students -->
                    <?php if($_SESSION['role'] === 'student'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../student/dashboard.php">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../student/scan.php">
                                <i class="fas fa-qrcode"></i> Scan QR
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- For Teachers -->
                    <?php if($_SESSION['role'] === 'teacher'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-chalkboard"></i> Manage
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="../teacher/dashboard.php">Dashboard</a></li>
                                <li><a class="dropdown-item" href="../teacher/attendance_reports.php">Attendance Reports</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" aria-expanded="false" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-shield"></i> Admin
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="../admin/dashboard.php">Dashboard</a></li>
                                <li><a class="dropdown-item" href="../admin/users.php">Manage Users</a></li>
                                <li><a class="dropdown-item" href="../admin/classes.php">Manage Classes</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../admin/settings.php">System Settings</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>

                    <!-- Common Items -->
                    
                    <li class="nav-item">
                        <a class="nav-link" href="../auth/logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="/classvibes/auth/login.php">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <button id="theme-toggle" class="btn btn-outline">
                        <i class="fas fa-moon"></i>
                    </button>
                </li>
                
            </ul>
        </div>
    </div>
</nav>