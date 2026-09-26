<?php
// scripts/migrate_mysql_to_mongodb.php
// One-time migration script to copy data from MySQL (pglife & homely_db) to MongoDB (homely_pg)

require_once __DIR__ . '/../includes/mongodb_connect.php';

$mysql_host = '127.0.0.1';
$mysql_user = 'root';
$mysql_pass = '';

echo "=== Starting MySQL to MongoDB Migration ===\n\n";

// 1. Migrate pglife database
$conn_pglife = @mysqli_connect($mysql_host, $mysql_user, $mysql_pass, 'pglife');
if ($conn_pglife) {
    echo "[+] Connected to MySQL database 'pglife'\n";

    $tables_pglife = [
        'users' => ['id', 'email', 'password', 'full_name', 'phone', 'gender', 'college_name', 'profile_image'],
        'cities' => ['id', 'name', 'icon'],
        'properties' => ['id', 'city_id', 'name', 'address', 'description', 'gender', 'rent', 'rating_clean', 'rating_food', 'rating_safety'],
        'amenities' => ['id', 'name', 'type', 'icon'],
        'properties_amenities' => ['id', 'property_id', 'amenity_id'],
        'testimonials' => ['id', 'property_id', 'user_name', 'content'],
        'interested_users_properties' => ['id', 'user_id', 'property_id'],
        'messages' => ['id', 'sender', 'text', 'image_path', 'created_at'],
        'bot_responses' => ['id', 'keyword', 'reply']
    ];

    foreach ($tables_pglife as $table => $fields) {
        $res = mysqli_query($conn_pglife, "SELECT * FROM `$table`");
        if ($res) {
            $count = 0;
            $db->$table->deleteMany([]); // Clear existing collection
            while ($row = mysqli_fetch_assoc($res)) {
                // Cast numeric fields
                if (isset($row['id'])) $row['id'] = (int)$row['id'];
                if (isset($row['city_id'])) $row['city_id'] = (int)$row['city_id'];
                if (isset($row['property_id'])) $row['property_id'] = (int)$row['property_id'];
                if (isset($row['amenity_id'])) $row['amenity_id'] = (int)$row['amenity_id'];
                if (isset($row['user_id'])) $row['user_id'] = (int)$row['user_id'];
                if (isset($row['rent'])) $row['rent'] = (int)$row['rent'];
                if (isset($row['rating_clean'])) $row['rating_clean'] = (float)$row['rating_clean'];
                if (isset($row['rating_food'])) $row['rating_food'] = (float)$row['rating_food'];
                if (isset($row['rating_safety'])) $row['rating_safety'] = (float)$row['rating_safety'];

                $db->$table->insertOne($row);
                $count++;
            }
            echo "    -> Migrated $count rows into collection '$table'\n";
        }
    }
    mysqli_close($conn_pglife);
} else {
    echo "[-] Could not connect to MySQL 'pglife' (Skip or check MySQL service)\n";
}

// 2. Migrate homely_db contacts table
$conn_homely = @mysqli_connect($mysql_host, $mysql_user, $mysql_pass, 'homely_db');
if ($conn_homely) {
    echo "\n[+] Connected to MySQL database 'homely_db'\n";
    $res = mysqli_query($conn_homely, "SELECT * FROM `contacts`");
    if ($res) {
        $count = 0;
        $db->contacts->deleteMany([]);
        while ($row = mysqli_fetch_assoc($res)) {
            if (isset($row['id'])) $row['id'] = (int)$row['id'];
            $db->contacts->insertOne($row);
            $count++;
        }
        echo "    -> Migrated $count rows into collection 'contacts'\n";
    }
    mysqli_close($conn_homely);
} else {
    echo "[-] Could not connect to MySQL 'homely_db' (Skip or check MySQL service)\n";
}

echo "\n=== Migration Complete ===\n";
