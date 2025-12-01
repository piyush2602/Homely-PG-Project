<?php
// includes/db_config.php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP default is empty
define('DB_NAME', 'homely_db');
define('DB_CHARSET', 'utf8mb4');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    error_log('DB connect failed: '.$mysqli->connect_error);
    die('Database connection failed.');
}
$mysqli->set_charset(DB_CHARSET);
