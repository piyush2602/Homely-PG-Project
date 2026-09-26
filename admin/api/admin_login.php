<?php
session_start();
require_once __DIR__ . '/../../includes/mongodb_connect.php';

header('Content-Type: application/json');

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Please enter both email and password.']);
    exit();
}

$hashedPassword = sha1($password);

// Hardcoded check or DB check for admin@gmail.com / admin123
if ($email === 'admin@gmail.com' && $password === 'admin123') {
    $_SESSION['is_admin'] = true;
    $_SESSION['admin_id'] = 999;
    $_SESSION['admin_email'] = 'admin@gmail.com';
    $_SESSION['admin_name'] = 'System Administrator';

    echo json_encode(['success' => true, 'message' => 'Admin login successful!']);
    exit();
}

// Fallback check in MongoDB users collection for role === 'admin'
$user = $db->users->findOne([
    'email' => $email,
    'password' => $hashedPassword
]);

if ($user && (!empty($user['role']) && $user['role'] === 'admin')) {
    $_SESSION['is_admin'] = true;
    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_email'] = $user['email'];
    $_SESSION['admin_name'] = $user['full_name'];

    echo json_encode(['success' => true, 'message' => 'Admin login successful!']);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid Admin Credentials!']);
exit();
