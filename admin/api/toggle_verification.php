<?php
require_once __DIR__ . '/../includes/admin_auth.php';
require_once __DIR__ . '/../../includes/mongodb_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$is_verified = isset($_POST['is_verified']) && ($_POST['is_verified'] === '1' || $_POST['is_verified'] === 'true' || $_POST['is_verified'] === true);

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid User ID.']);
    exit;
}

// Update verification status in MongoDB
$updateResult = $db->users->updateOne(
    ['id' => $user_id],
    ['$set' => ['is_verified' => $is_verified]]
);

echo json_encode([
    'success' => true,
    'message' => $is_verified ? 'User marked as Verified.' : 'User marked as Unverified.',
    'is_verified' => $is_verified
]);
