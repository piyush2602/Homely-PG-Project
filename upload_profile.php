<?php
session_start();
require "includes/mongodb_connect.php";

if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
    die();
}

$user_id = (int)$_SESSION["user_id"];

if (!empty($_FILES['profile_image']['name'])) {

    // Get image size
    $image_info = getimagesize($_FILES['profile_image']['tmp_name']);
    $width = $image_info[0];
    $height = $image_info[1];

    // Minimum required dimensions
    $min_width = 563;
    $min_height = 688;

    // Check dimensions
    if ($width < $min_width || $height < $min_height) {
        $_SESSION['error'] = "Image must be at least {$min_width}×{$min_height} pixels.";
        header("Location: dashboard.php");
        exit();
    }

    // File upload process
    $file_name = time() . "_" . basename($_FILES['profile_image']['name']);
    $target_dir = "uploads/profile/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
        $db->users->updateOne(['id' => $user_id], ['$set' => ['profile_image' => $target_file]]);
    }
}

header("Location: dashboard.php");
exit();

