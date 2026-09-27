<?php
session_start();
require_once __DIR__ . '/../../includes/mongodb_connect.php';

header('Content-Type: application/json');

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$id          = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$name        = isset($_POST['name']) ? trim($_POST['name']) : '';
$city_id     = isset($_POST['city_id']) ? (int)$_POST['city_id'] : 1;
$address     = isset($_POST['address']) ? trim($_POST['address']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$gender      = isset($_POST['gender']) ? trim($_POST['gender']) : 'unisex';
$rent        = isset($_POST['rent']) ? (int)$_POST['rent'] : 0;

$rating_clean  = isset($_POST['rating_clean']) ? (float)$_POST['rating_clean'] : 4.5;
$rating_food   = isset($_POST['rating_food']) ? (float)$_POST['rating_food'] : 4.5;
$rating_safety = isset($_POST['rating_safety']) ? (float)$_POST['rating_safety'] : 4.5;

$selected_amenities = isset($_POST['amenities']) ? (array)$_POST['amenities'] : [];

// Optional tenant review / feedback fields
$reviewer_name  = isset($_POST['reviewer_name']) ? trim($_POST['reviewer_name']) : '';
$feedback_text  = isset($_POST['feedback_text']) ? trim($_POST['feedback_text']) : '';

if (empty($name) || empty($address) || $rent <= 0) {
    echo json_encode(['success' => false, 'message' => 'Please provide property name, address, and valid rent.']);
    exit();
}

if ($id > 0) {
    // Update existing property
    $db->properties->updateOne(['id' => $id], [
        '$set' => [
            'city_id'       => $city_id,
            'name'          => $name,
            'address'       => $address,
            'description'   => $description,
            'gender'        => $gender,
            'rent'          => $rent,
            'rating_clean'  => $rating_clean,
            'rating_food'   => $rating_food,
            'rating_safety' => $rating_safety
        ]
    ]);
    $property_id = $id;
} else {
    // Insert new property with auto-increment ID
    $max_prop = $db->properties->findOne([], ['sort' => ['id' => -1]]);
    $property_id = ($max_prop && isset($max_prop['id'])) ? (int)$max_prop['id'] + 1 : 1;

    $db->properties->insertOne([
        'id'            => $property_id,
        'city_id'       => $city_id,
        'name'          => $name,
        'address'       => $address,
        'description'   => $description,
        'gender'        => $gender,
        'rent'          => $rent,
        'rating_clean'  => $rating_clean,
        'rating_food'   => $rating_food,
        'rating_safety' => $rating_safety
    ]);
}

// Update amenities mapping
$db->properties_amenities->deleteMany(['property_id' => $property_id]);
$pa_max = $db->properties_amenities->findOne([], ['sort' => ['id' => -1]]);
$pa_id = ($pa_max && isset($pa_max['id'])) ? (int)$pa_max['id'] + 1 : 1;

foreach ($selected_amenities as $amenity_id) {
    $db->properties_amenities->insertOne([
        'id'          => $pa_id++,
        'property_id' => $property_id,
        'amenity_id'  => (int)$amenity_id
    ]);
}

// Handle Image Upload if provided
if (!empty($_FILES['image']['name'])) {
    $target_dir = __DIR__ . "/../../img/properties/{$property_id}/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . "1.jpg";
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
}

// Save or Update Optional Testimonial / Feedback
if (!empty($reviewer_name) || !empty($feedback_text)) {
    $existing_t = $db->testimonials->findOne(['property_id' => $property_id]);
    if ($existing_t) {
        $db->testimonials->updateOne(
            ['property_id' => $property_id],
            ['$set' => ['user_name' => $reviewer_name, 'content' => $feedback_text]]
        );
    } else {
        $t_max = $db->testimonials->findOne([], ['sort' => ['id' => -1]]);
        $t_id = ($t_max && isset($t_max['id'])) ? (int)$t_max['id'] + 1 : 1;
        $db->testimonials->insertOne([
            'id'          => $t_id,
            'property_id' => $property_id,
            'user_name'   => $reviewer_name,
            'content'     => $feedback_text
        ]);
    }
}

echo json_encode(['success' => true, 'message' => 'Property saved successfully!']);
exit();
