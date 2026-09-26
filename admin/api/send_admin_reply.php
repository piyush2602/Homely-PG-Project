<?php
session_start();
require_once __DIR__ . '/../../includes/mongodb_connect.php';

header('Content-Type: application/json');

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$text    = isset($_POST['text']) ? trim($_POST['text']) : '';

if ($user_id <= 0 || empty($text)) {
    echo json_encode(['success' => false, 'message' => 'Please select a user and type a message.']);
    exit();
}

$db->messages->insertOne([
    'user_id'    => $user_id,
    'sender'     => 'admin',
    'text'       => $text,
    'image_path' => null,
    'is_seen'    => false,
    'created_at' => date('Y-m-d H:i:s')
]);


echo json_encode(['success' => true, 'message' => 'Reply sent to user']);
exit();
