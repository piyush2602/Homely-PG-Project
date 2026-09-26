<?php
require_once __DIR__ . '/../includes/admin_auth.php';
require_once __DIR__ . '/../../includes/mongodb_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid User ID.']);
    exit;
}

// Delete user from users collection
$deleteResult = $db->users->deleteOne(['id' => $id, 'role' => ['$ne' => 'admin']]);

if ($deleteResult->getDeletedCount() > 0) {
    // Optionally clean up messages and interested properties for this user
    $db->messages->deleteMany(['user_id' => $id]);
    $db->interested_users_properties->deleteMany(['user_id' => $id]);

    echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found or cannot be deleted.']);
}
