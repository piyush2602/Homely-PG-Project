<?php
// includes/mongodb_connect.php
// Unified MongoDB connection — supports .env file, Environment Variables, and local fallback

require_once __DIR__ . '/../vendor/autoload.php';

// Automatically load .env file if present
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            putenv("{$name}={$value}");
            $_ENV[$name] = $value;
        }
    }
}

use MongoDB\Client;

// Reads MONGO_URI from .env file or Environment Variable (e.g. Render), defaulting to local MongoDB
$mongoUri = getenv('MONGO_URI') ?: 'mongodb://127.0.0.1:27017';
$mongoDbName = getenv('MONGO_DB') ?: 'homely_pg';

$mongoClient = new Client($mongoUri);
$db = $mongoClient->selectDatabase($mongoDbName);


