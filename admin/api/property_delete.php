<?php
session_start();
require_once __DIR__ . '/../../includes/mongodb_connect.php';

header('Content-Type: application/json');

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid Property ID']);
    exit();
}

$db->properties->deleteOne(['id' => $id]);
$db->properties_amenities->deleteMany(['property_id' => $id]);
$db->testimonials->deleteMany(['property_id' => $id]);
$db->interested_users_properties->deleteMany(['property_id' => $id]);

echo json_encode(['success' => true, 'message' => 'Property deleted successfully']);
exit();
