<?php
session_start();
require_once __DIR__ . '/../../includes/mongodb_connect.php';

header('Content-Type: application/json');

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

$current_password = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
$new_password     = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required. Please fill in all fields.']);
    exit();
}

if ($new_password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'New password and confirm password do not match.']);
    exit();
}

if (strlen($new_password) < 6) {
    echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters long.']);
    exit();
}

$admin_email = $_SESSION['admin_email'] ?? 'admin@gmail.com';
$admin_id    = $_SESSION['admin_id'] ?? 999;

// Search for current admin in MongoDB users collection
$user = $db->users->findOne(['email' => $admin_email]);
if (!$user) {
    $user = $db->users->findOne(['role' => 'admin']);
}

$is_current_valid = false;

if ($user && isset($user['password'])) {
    if ($user['password'] === sha1($current_password)) {
        $is_current_valid = true;
    }
} else {
    // Default initial fallback password check
    if ($current_password === 'admin123') {
        $is_current_valid = true;
    }
}

if (!$is_current_valid) {
    echo json_encode(['success' => false, 'message' => 'Incorrect current password! Please verify your existing password.']);
    exit();
}

// Update password in MongoDB
$new_hashed_password = sha1($new_password);

if ($user) {
    $db->users->updateOne(
        ['_id' => $user['_id']],
        ['$set' => ['password' => $new_hashed_password, 'role' => 'admin']]
    );
} else {
    // Insert initial admin user document into MongoDB
    $db->users->insertOne([
        'id'           => 999,
        'email'        => 'admin@gmail.com',
        'password'     => $new_hashed_password,
        'full_name'    => 'System Administrator',
        'phone'        => '9999999999',
        'gender'       => 'unisex',
        'college_name' => 'Homely PG HQ',
        'role'         => 'admin'
    ]);
}

echo json_encode(['success' => true, 'message' => 'Password updated successfully!']);
exit();
