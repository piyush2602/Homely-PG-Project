<?php
session_start();
require_once "includes/mongodb_connect.php";

// Optionally fetch city property counts from MongoDB
$cities_data = [
    ['name' => 'Delhi', 'img' => 'img/delhi.png', 'count' => '120+ PGs'],
    ['name' => 'Mumbai', 'img' => 'img/mumbai.png', 'count' => '95+ PGs'],
    ['name' => 'Bengaluru', 'img' => 'img/bangalore.png', 'count' => '150+ PGs'],
    ['name' => 'Hyderabad', 'img' => 'img/hyderabad.png', 'count' => '80+ PGs']
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homely PG | Find Your Perfect PG Accommodation</title>

    <?php include "includes/head_links.php"; ?>
    <link href="css/home.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="favico.ico">
</head>

<body>
    <?php include "includes/header.php"; ?>

    <!-- Hero Banner Section -->
    <section class="hero-banner">
        <div class="container position-relative">
            <span class="banner-badge">
                <i class="fas fa-home"></i> 100% Verified Co-Living &amp; Student PGs
            </span>
            <h1 class="banner-title">Happiness per Square Foot</h1>
            <p class="banner-subtitle">
                Discover clean, safe, and fully furnished PG accommodations across India’s top tech hubs with zero brokerage.
            </p>

            <!-- Search Card -->
            <form id="search-form" action="property_list.php" method="GET">
                <div class="search-card">
                    <i class="fas fa-map-marker-alt search-icon"></i>
                    <input type="text" class="search-input" id="city" name="city" placeholder="Enter city (e.g. Delhi, Mumbai, Bengaluru, Hyderabad)..." required />
                    <button type="submit" class="btn-search">
                        <i class="fas fa-search"></i> Search PGs
                    </button>
                </div>
            </form>

            <!-- Quick City Search Pills -->
            <div class="quick-city-pills">
                <span class="text-white-50 small align-self-center">Popular:</span>
                <a href="property_list.php?city=Delhi" class="city-pill">Delhi</a>
                <a href="property_list.php?city=Mumbai" class="city-pill">Mumbai</a>
                <a href="property_list.php?city=Bengaluru" class="city-pill">Bengaluru</a>
                <a href="property_list.php?city=Hyderabad" class="city-pill">Hyderabad</a>
            </div>
        </div>
    </section>

    <!-- Key Benefits Bar -->
    <div class="benefits-bar">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <h4 class="benefit-title">100% Verified</h4>
                            <p class="benefit-desc">Inspected for safety &amp; hygiene</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div>
                            <h4 class="benefit-title">Zero Brokerage</h4>
                            <p class="benefit-desc">No middleman commissions</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-wifi"></i>
                        </div>
                        <div>
                            <h4 class="benefit-title">High Speed WiFi</h4>
                            <p class="benefit-desc">100+ Mbps fiber internet</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <div>
                            <h4 class="benefit-title">24/7 Support</h4>
                            <p class="benefit-desc">Instant online chatbot assistance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Major Cities Section -->
    <section class="cities-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Top Destinations</span>
                <h2 class="section-title">Explore PGs in Major Metro Cities</h2>
            </div>
            <div class="row g-4 justify-content-center">
                <?php foreach ($cities_data as $city): ?>
                    <div class="col-6 col-md-3">
                        <a href="property_list.php?city=<?= urlencode($city['name']) ?>" class="city-card-item">
                            <div class="city-card-box">
                                <div class="city-img-wrapper">
                                    <img src="<?= $city['img'] ?>" alt="<?= htmlspecialchars($city['name']) ?>" />
                                </div>
                                <h3 class="city-name"><?= htmlspecialchars($city['name']) ?></h3>
                                <span class="city-count-badge"><?= $city['count'] ?></span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Chatbot Banner CTA -->
            <div class="chat-cta-box">
                <div>
                    <h3 class="chat-cta-title">Need Help Finding the Right Room?</h3>
                    <p class="chat-cta-desc">Talk to our 24/7 AI Chatbot for instant answers about rent, food, and facilities.</p>
                </div>
                <a href="chat.php" class="btn-chat-launch">
                    <i class="fas fa-comments"></i> Start AI Chat
                </a>
            </div>
        </div>
    </section>

    <?php
    include "includes/signup_modal.php";
    include "includes/login_modal.php";
    include "includes/footer.php";
    ?>

</body>

</html>