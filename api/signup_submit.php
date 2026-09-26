<?php
require("../includes/mongodb_connect.php");

$full_name = $_POST['full_name'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$password = sha1($password);
$college_name = $_POST['college_name'] ?? '';
$gender = $_POST['gender'] ?? '';

$existing_user = $db->users->findOne(['email' => $email]);
if ($existing_user) {
    $response = array("success" => false, "message" => "This email id is already registered with us!");
    echo json_encode($response);
    return;
}

$max_id_doc = $db->users->findOne([], ['sort' => ['id' => -1]]);
$new_id = ($max_id_doc && isset($max_id_doc['id'])) ? (int)$max_id_doc['id'] + 1 : 1;

$db->users->insertOne([
    'id'           => $new_id,
    'email'        => $email,
    'password'     => $password,
    'full_name'    => $full_name,
    'phone'        => $phone,
    'gender'       => $gender,
    'college_name' => $college_name
]);

$response = array("success" => true, "message" => "Your account has been created successfully!");
echo json_encode($response);

