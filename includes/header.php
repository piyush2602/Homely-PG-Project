<div class="header sticky-top">
    <nav class="navbar navbar-expand-md navbar-light">
        <a class="navbar-brand" href="index.php">
            <img src="img/logo.png" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#my-navbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="my-navbar">
            <?php
            // ensure session is started (only start if not already)
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            ?>
            <ul class="navbar-nav">
                <!-- ALWAYS VISIBLE LINKS -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <div class="nav-vl"></div>

                <li class="nav-item">
                    <a class="nav-link" href="about.php">
                        <i class="fas fa-info-circle"></i> About
                    </a>
                </li>
                <div class="nav-vl"></div>

                <!-- AUTH AREA: show signup/login for guests, profile/logout for logged-in users -->
                <?php if (empty($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#signup-modal">
                            <i class="fas fa-user"></i> Signup
                        </a>
                    </li>
                    <div class="nav-vl"></div>

                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#login-modal">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-user-circle"></i>
                            <?= htmlspecialchars($_SESSION['full_name'] ?? 'Account') ?>
                        </a>
                    </li>
                    <div class="nav-vl"></div>

                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</div>
<div id="loading">
</div>