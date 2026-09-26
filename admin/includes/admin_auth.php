<?php
// admin/includes/admin_auth.php
// Session guard for protecting all admin routes

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}
