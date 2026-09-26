<?php
session_start();

require "../includes/mongodb_connect.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("success" => false, "is_logged_in" => false));
    return;
}

$user_id = (int)$_SESSION['user_id'];
$property_id = isset($_GET["property_id"]) ? (int)$_GET["property_id"] : 0;

$existing = $db->interested_users_properties->findOne([
    'user_id' => $user_id,
    'property_id' => $property_id
]);

if ($existing) {
    $db->interested_users_properties->deleteOne([
        'user_id' => $user_id,
        'property_id' => $property_id
    ]);
    echo json_encode(array("success" => true, "is_interested" => false, "property_id" => $property_id));
} else {
    $db->interested_users_properties->insertOne([
        'user_id' => $user_id,
        'property_id' => $property_id
    ]);
    echo json_encode(array("success" => true, "is_interested" => true, "property_id" => $property_id));
}

