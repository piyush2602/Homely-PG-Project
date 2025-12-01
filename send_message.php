<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

$name    = isset($_POST['name']) ? trim($_POST['name']) : '';
$email   = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

$errors = [];
if (empty($name)) $errors[] = "Please enter your name.";
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Please enter a valid email.";
if (empty($message)) $errors[] = "Please enter a message.";

if (!empty($errors)) {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_old'] = ['name' => $name, 'email' => $email, 'message' => $message];
    header('Location: contact.php');
    exit;
}

require_once __DIR__ . '/includes/db_config.php';
$ip_address = $_SERVER['REMOTE_ADDR'] ?? '';

$stmt = $mysqli->prepare("INSERT INTO contacts (name, email, message, ip_address) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    error_log("Prepare failed: " . $mysqli->error);
    $_SESSION['contact_errors'] = ["Server error."];
    header('Location: contact.php');
    exit;
}
$stmt->bind_param('ssss', $name, $email, $message, $ip_address);
if ($stmt->execute()) {
    $_SESSION['contact_success'] = "Thanks — your message was sent.";
    unset($_SESSION['contact_old']);
} else {
    error_log("Execute failed: " . $stmt->error);
    $_SESSION['contact_errors'] = ["Server error."];
    $_SESSION['contact_old'] = ['name' => $name, 'email' => $email, 'message' => $message];
}
$stmt->close();
$mysqli->close();

header('Location: contact.php');
exit;
