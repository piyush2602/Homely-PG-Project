<?php
// scripts/seed_mongodb_atlas.php
// Seeds MongoDB Atlas database (homely_pg) with initial data

require_once __DIR__ . '/../includes/mongodb_connect.php';

echo "=== Seeding MongoDB Atlas (Database: {$mongoDbName}) ===\n\n";

// 1. Cities
$cities = [
    ['id' => 1, 'name' => 'Delhi', 'icon' => 'img/delhi.png'],
    ['id' => 2, 'name' => 'Mumbai', 'icon' => 'img/mumbai.png'],
    ['id' => 3, 'name' => 'Bengaluru', 'icon' => 'img/bengaluru.png'],
    ['id' => 4, 'name' => 'Hyderabad', 'icon' => 'img/hyderabad.png']
];

$db->cities->deleteMany([]);
$db->cities->insertMany($cities);
echo "[+] Inserted " . count($cities) . " cities.\n";

// 2. Properties
$properties = [
    [
        'id' => 1,
        'city_id' => 1,
        'name' => 'Ganpati Heights PG',
        'address' => 'Plot No. 12, Sector 14, Dwarka, Delhi',
        'description' => 'Spacious and fully furnished PG with modern amenities, nutritious meals, and 24/7 high-speed WiFi. Perfect for students and working professionals.',
        'gender' => 'unisex',
        'rent' => 8500,
        'rating_clean' => 4.8,
        'rating_food' => 4.5,
        'rating_safety' => 4.9
    ],
    [
        'id' => 2,
        'city_id' => 1,
        'name' => 'Navkar Residency',
        'address' => 'Near Laxmi Nagar Metro Station, Laxmi Nagar, Delhi',
        'description' => 'Comfortable stay located right next to the metro station. Includes daily housekeeping, AC rooms, and attached washrooms.',
        'gender' => 'male',
        'rent' => 9500,
        'rating_clean' => 4.6,
        'rating_food' => 4.2,
        'rating_safety' => 4.7
    ],
    [
        'id' => 3,
        'city_id' => 2,
        'name' => 'Sunshine Luxury PG',
        'address' => 'Opposite Mithibai College, Vile Parle West, Mumbai',
        'description' => 'Premium PG for females offering sea-view balcony rooms, biometric entry, home-cooked food, and peaceful study atmosphere.',
        'gender' => 'female',
        'rent' => 14000,
        'rating_clean' => 4.9,
        'rating_food' => 4.8,
        'rating_safety' => 5.0
    ],
    [
        'id' => 4,
        'city_id' => 3,
        'name' => 'Tech Park Stays',
        'address' => 'Near Manyata Tech Park, Nagavara, Bengaluru',
        'description' => 'Modern co-living space designed for IT professionals. Includes gaming zone, high-speed fiber internet, and 3-time meal facility.',
        'gender' => 'unisex',
        'rent' => 11000,
        'rating_clean' => 4.7,
        'rating_food' => 4.4,
        'rating_safety' => 4.8
    ]
];

$db->properties->deleteMany([]);
$db->properties->insertMany($properties);
echo "[+] Inserted " . count($properties) . " properties.\n";

// 3. Amenities
$amenities = [
    ['id' => 1, 'name' => 'Wifi', 'type' => 'common', 'icon' => 'img/amenities/wifi.svg'],
    ['id' => 2, 'name' => 'AC', 'type' => 'room', 'icon' => 'img/amenities/ac.svg'],
    ['id' => 3, 'name' => 'RO Water', 'type' => 'common', 'icon' => 'img/amenities/rowater.svg'],
    ['id' => 4, 'name' => 'TV', 'type' => 'common', 'icon' => 'img/amenities/tv.svg'],
    ['id' => 5, 'name' => 'Laundry', 'type' => 'washroom', 'icon' => 'img/amenities/laundry.svg'],
    ['id' => 6, 'name' => 'Cleaning', 'type' => 'common', 'icon' => 'img/amenities/cleaning.svg'],
    ['id' => 7, 'name' => 'Power Backup', 'type' => 'common', 'icon' => 'img/amenities/powerbackup.svg'],
    ['id' => 8, 'name' => 'Geyser', 'type' => 'washroom', 'icon' => 'img/amenities/geyser.svg']
];

$db->amenities->deleteMany([]);
$db->amenities->insertMany($amenities);
echo "[+] Inserted " . count($amenities) . " amenities.\n";

// 4. Properties Amenities Mapping
$properties_amenities = [
    ['id' => 1, 'property_id' => 1, 'amenity_id' => 1],
    ['id' => 2, 'property_id' => 1, 'amenity_id' => 2],
    ['id' => 3, 'property_id' => 1, 'amenity_id' => 3],
    ['id' => 4, 'property_id' => 1, 'amenity_id' => 5],
    ['id' => 5, 'property_id' => 1, 'amenity_id' => 6],
    ['id' => 6, 'property_id' => 2, 'amenity_id' => 1],
    ['id' => 7, 'property_id' => 2, 'amenity_id' => 2],
    ['id' => 8, 'property_id' => 2, 'amenity_id' => 3],
    ['id' => 9, 'property_id' => 3, 'amenity_id' => 1],
    ['id' => 10, 'property_id' => 3, 'amenity_id' => 2],
    ['id' => 11, 'property_id' => 3, 'amenity_id' => 4],
    ['id' => 12, 'property_id' => 3, 'amenity_id' => 5],
    ['id' => 13, 'property_id' => 4, 'amenity_id' => 1],
    ['id' => 14, 'property_id' => 4, 'amenity_id' => 2],
    ['id' => 15, 'property_id' => 4, 'amenity_id' => 7]
];

$db->properties_amenities->deleteMany([]);
$db->properties_amenities->insertMany($properties_amenities);
echo "[+] Inserted " . count($properties_amenities) . " property-amenity links.\n";

// 5. Testimonials
$testimonials = [
    ['id' => 1, 'property_id' => 1, 'user_name' => 'Aman Sharma', 'content' => 'Staying at Ganpati Heights has been an amazing experience. The food tastes like home and the staff is very polite.'],
    ['id' => 2, 'property_id' => 1, 'user_name' => 'Priya Patel', 'content' => 'Super safe environment for girls with excellent cleanliness and fast WiFi.'],
    ['id' => 3, 'property_id' => 2, 'user_name' => 'Rohan Gupta', 'content' => 'Best location in Laxmi Nagar! Very close to metro station and coaching institutes.'],
    ['id' => 4, 'property_id' => 3, 'user_name' => 'Sneha Roy', 'content' => 'Very hygienic and luxurious PG. Highly recommended for students in Mumbai.'],
    ['id' => 5, 'property_id' => 4, 'user_name' => 'Karthik Reddy', 'content' => 'Perfect co-living space near Manyata Tech Park. Great community and quick support team.']
];

$db->testimonials->deleteMany([]);
$db->testimonials->insertMany($testimonials);
echo "[+] Inserted " . count($testimonials) . " testimonials.\n";

// 6. Bot Responses for Chat
$bot_responses = [
    ['id' => 1, 'keyword' => 'hello', 'reply' => 'Hello! Welcome to Homely PG Support. How can I help you today?'],
    ['id' => 2, 'keyword' => 'hi', 'reply' => 'Hi there! Looking for a PG in Delhi, Mumbai, Bengaluru, or Hyderabad?'],
    ['id' => 3, 'keyword' => 'rent', 'reply' => 'Our PG rents range from ₹8,500 to ₹14,000 per month depending on the location and room type.'],
    ['id' => 4, 'keyword' => 'location', 'reply' => 'We have prime PG accommodations in Delhi, Mumbai, Bengaluru, and Hyderabad.'],
    ['id' => 5, 'keyword' => 'food', 'reply' => 'Yes! Breakfast, lunch, and dinner are served daily with hygienic home-cooked meals.'],
    ['id' => 6, 'keyword' => 'contact', 'reply' => 'You can reach out via our Contact Us page or drop your query right here in the chat!']
];

$db->bot_responses->deleteMany([]);
$db->bot_responses->insertMany($bot_responses);
echo "[+] Inserted " . count($bot_responses) . " chatbot responses.\n";

// 7. Seed Demo User
$demo_user = [
    'id' => 1,
    'email' => 'demo@homelypg.com',
    'password' => sha1('password123'),
    'full_name' => 'Piyush Agrawal',
    'phone' => '9876543210',
    'gender' => 'male',
    'college_name' => 'Delhi University',
    'profile_image' => 'img/user.png'
];

$db->users->deleteMany(['email' => 'demo@homelypg.com']);
$db->users->insertOne($demo_user);
echo "[+] Inserted demo user (Email: demo@homelypg.com | Password: password123).\n";

// 8. Seed Default Admin User
$admin_user = [
    'id' => 999,
    'email' => 'admin@gmail.com',
    'password' => sha1('admin123'),
    'full_name' => 'System Administrator',
    'phone' => '9999999999',
    'gender' => 'unisex',
    'college_name' => 'Homely PG HQ',
    'role' => 'admin'
];

$db->users->deleteMany(['email' => 'admin@gmail.com']);
$db->users->insertOne($admin_user);
echo "[+] Inserted default Admin user (Email: admin@gmail.com | Password: admin123).\n";

echo "\n🎉 === MongoDB Atlas Seeding Completed Successfully! ===\n";

