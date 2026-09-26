<?php
require_once __DIR__ . '/../includes/admin_auth.php';
require_once __DIR__ . '/../../includes/mongodb_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID specified.']);
    exit;
}

// Delete all messages associated with this user ID
$deleteResult = $db->messages->deleteMany(['user_id' => $user_id]);

echo json_encode([
    'success' => true,
    'message' => 'Chat history cleared successfully.',
    'deleted_count' => $deleteResult->getDeletedCount()
]);
