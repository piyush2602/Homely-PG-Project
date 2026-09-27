<?php
// admin/includes/admin_header.php
require_once __DIR__ . '/admin_auth.php';

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Homely PG</title>
    <link rel="icon" type="image/png" href="img/admin_favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="css/admin.css" rel="stylesheet" />
</head>
<body class="<?= $current_page === 'chat.php' ? 'page-chat' : '' ?>">

<div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <a href="dashboard.php" class="admin-brand">
            <i class="fas fa-shield-alt"></i> Homely Admin
        </a>
        <ul class="sidebar-nav">
            <li>
                <a href="dashboard.php" class="<?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="properties.php" class="<?= in_array($current_page, ['properties.php', 'property_add.php', 'property_edit.php']) ? 'active' : '' ?>">
                    <i class="fas fa-hotel"></i> Manage PGs / Hotels
                </a>
            </li>
            <li>
                <a href="users.php" class="<?= $current_page === 'users.php' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> User Accounts
                </a>
            </li>
            <li>
                <?php
                require_once __DIR__ . '/../../includes/mongodb_connect.php';
                $total_unseen_user_msgs = $db->messages->countDocuments(['sender' => 'user', 'is_seen' => false]);
                ?>
                <a href="chat.php" class="<?= $current_page === 'chat.php' ? 'active' : '' ?> d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-comments mr-2"></i> Live Support Chat</span>
                    <?php if ($total_unseen_user_msgs > 0): ?>
                        <span class="badge badge-danger font-weight-bold">+<?= $total_unseen_user_msgs ?></span>
                    <?php endif; ?>
                </a>
            </li>

            <li>
                <a href="change_password.php" class="<?= $current_page === 'change_password.php' ? 'active' : '' ?>">
                    <i class="fas fa-key"></i> Change Password
                </a>
            </li>
            <li>
                <a href="../index.php" target="_blank">
                    <i class="fas fa-globe"></i> View Website
                </a>
            </li>
            <li class="mt-auto">
                <a href="logout.php" class="text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <main class="admin-content">
        <div class="admin-topbar">
            <h2 class="topbar-title">
                <?php
                if ($current_page === 'dashboard.php') echo 'Dashboard Overview';
                elseif (in_array($current_page, ['properties.php', 'property_add.php', 'property_edit.php'])) echo 'PG / Hotel Management';
                elseif ($current_page === 'users.php') echo 'Registered Users';
                elseif ($current_page === 'chat.php') echo 'Live Admin Support Chat';
                elseif ($current_page === 'change_password.php') echo 'Change Admin Password';
                else echo 'Admin Portal';
                ?>
            </h2>
            <div class="d-flex align-items-center">
                <?php if ($current_page === 'chat.php'): ?>
                    <button class="btn btn-sm btn-outline-primary mr-3" onclick="location.reload();" style="border-radius: 8px;">
                        <i class="fas fa-sync-alt mr-1"></i> Refresh
                    </button>
                <?php endif; ?>
                <div class="admin-user-pill">
                    <i class="fas fa-user-circle text-primary"></i>
                    <span><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
                </div>
            </div>
        </div>
