<?php
session_start();
require("../includes/mongodb_connect.php");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$password = sha1($password);

$user = $db->users->findOne([
    'email' => $email,
    'password' => $password
]);

if (!$user) {
    $response = array("success" => false, "message" => "Login failed! Invalid email or password.");
    echo json_encode($response);
    return;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['email'] = $user['email'];

$response = array("success" => true, "message" => "Login successful!");
echo json_encode($response);

