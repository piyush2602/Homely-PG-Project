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

                <li class="nav-item">
                    <a class="nav-link" href="about.php">
                        <i class="fas fa-info-circle"></i> About
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-admin" href="admin/login.php">
                        <i class="fas fa-user-shield"></i> Admin Portal
                    </a>
                </li>

                <?php if (!empty($_SESSION['user_id'])): ?>
                    <?php
                    require_once __DIR__ . '/mongodb_connect.php';
                    $user_id_val = (int)$_SESSION['user_id'];
                    $nav_user = $db->users->findOne(['id' => $user_id_val]);
                    $nav_pic = (!empty($nav_user['profile_image']) && file_exists(__DIR__ . '/../' . $nav_user['profile_image'])) ? $nav_user['profile_image'] : null;
                    ?>
                <?php endif; ?>

                <!-- AUTH AREA: show signup/login for guests, profile/logout for logged-in users -->
                <?php if (empty($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link nav-btn-signup" href="#" data-toggle="modal" data-target="#signup-modal">
                            <i class="fas fa-user-plus"></i> Signup
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-btn-login" href="#" data-toggle="modal" data-target="#login-modal">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link nav-link-user" href="dashboard.php">
                            <?php if (!empty($nav_pic)): ?>
                                <img src="<?= htmlspecialchars($nav_pic) ?>" class="rounded-circle mr-1" style="width: 24px; height: 24px; object-fit: cover;" alt="Profile" />
                            <?php else: ?>
                                <i class="fas fa-user-circle mr-1"></i>
                            <?php endif; ?>
                            <span><?= htmlspecialchars($nav_user['full_name'] ?? $_SESSION['full_name'] ?? 'Account') ?></span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-btn-logout" href="logout.php">
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