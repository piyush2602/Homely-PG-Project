<?php
session_start();
require "includes/mongodb_connect.php";

if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
    die();
}

$user_id = (int)$_SESSION["user_id"];

if (!empty($_FILES['id_card']['name'])) {
    $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
    $ext = strtolower(pathinfo($_FILES['id_card']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_exts)) {
        $_SESSION['id_error'] = "Invalid file format. Please upload JPG, PNG, WEBP, or PDF.";
        header("Location: dashboard.php");
        exit();
    }

    $file_name = time() . "_" . rand(1000, 9999) . "." . $ext;
    $target_dir = "uploads/id_cards/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES['id_card']['tmp_name'], $target_file)) {
        $db->users->updateOne(['id' => $user_id], ['$set' => ['id_card_path' => $target_file]]);
        $_SESSION['id_success'] = "Identity card uploaded successfully! Awaiting admin verification.";
    } else {
        $_SESSION['id_error'] = "Failed to save uploaded file. Please try again.";
    }
} else {
    $_SESSION['id_error'] = "Please select an identity card file to upload.";
}

header("Location: dashboard.php");
exit();
