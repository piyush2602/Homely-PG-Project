<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us | Homely PG</title>

    <?php include "includes/head_links.php"; ?>
    <link href="css/about.css" rel="stylesheet" />
</head>

<body>

    <?php include "includes/header.php"; ?>

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container position-relative">
            <span class="hero-badge">
                <i class="fas fa-sparkles"></i> Redefining Co-Living Spaces
            </span>
            <h1 class="hero-title">Finding Your Perfect PG Feels Like Coming Home</h1>
            <p class="hero-subtitle">
                Homely PG connects thousands of students and working professionals with 100% verified, hygienic, and affordable accommodations across India's top tech and educational hubs.
            </p>
            <div class="hero-btn-group">
                <a href="index.php" class="btn-gradient-primary">
                    <i class="fas fa-search"></i> Explore Properties
                </a>
                <a href="contact.php" class="btn-outline-glow">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Key Statistics -->
    <div class="container stats-wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-3">
                <div class="stats-card">
                    <div class="stat-number">5,000+</div>
                    <div class="stat-label">Happy Residents</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stats-card">
                    <div class="stat-number">400+</div>
                    <div class="stat-label">Verified PGs</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stats-card">
                    <div class="stat-number">4</div>
                    <div class="stat-label">Major Metro Cities</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stats-card">
                    <div class="stat-number">4.9 ★</div>
                    <div class="stat-label">Satisfaction Score</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mission & Vision -->
    <section class="mv-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mv-card">
                        <div class="mv-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="mv-title">Our Mission</h3>
                        <p class="mv-desc">
                            To eliminate room-hunting hassle by offering 100% physically verified PG accommodations with zero brokerage, transparent pricing, and instant online booking convenience.
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mv-card">
                        <div class="mv-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="mv-title">Our Vision</h3>
                        <p class="mv-desc">
                            To build India’s largest, safest, and most trusted co-living network where every student and young professional feels safe, valued, and right at home.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Grid -->
    <section class="features-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Why Choose Us</span>
                <h2 class="section-title">Everything You Need for a Seamless Stay</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon-circle">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="feature-box-title">100% Verified PGs</h4>
                        <p class="feature-box-desc">Every listing undergoes physical inspection for safety, cleanliness, and authentic property photos.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon-circle">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h4 class="feature-box-title">Zero Brokerage</h4>
                        <p class="feature-box-desc">Book directly without paying middleman fees or hidden commissions. Clear and upfront pricing always.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon-circle">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h4 class="feature-box-title">3-Tier Security</h4>
                        <p class="feature-box-desc">24/7 CCTV surveillance, biometric access gates, and dedicated resident wardens for complete peace of mind.</p>
                    </div>
                </div>
                <div class="col-md-4 mt-4">
                    <div class="feature-box">
                        <div class="feature-icon-circle">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h4 class="feature-box-title">Homestyle Meals</h4>
                        <p class="feature-box-desc">Fresh, nutritious, and delicious meals prepared daily in sanitized kitchen setups.</p>
                    </div>
                </div>
                <div class="col-md-4 mt-4">
                    <div class="feature-box">
                        <div class="feature-icon-circle">
                            <i class="fas fa-wifi"></i>
                        </div>
                        <h4 class="feature-box-title">High-Speed Fiber WiFi</h4>
                        <p class="feature-box-desc">Seamless 100+ Mbps fiber internet connection designed for online classes, remote work, and streaming.</p>
                    </div>
                </div>
                <div class="col-md-4 mt-4">
                    <div class="feature-box">
                        <div class="feature-icon-circle">
                            <i class="fas fa-broom"></i>
                        </div>
                        <h4 class="feature-box-title">Daily Housekeeping</h4>
                        <p class="feature-box-desc">Professional cleaning staff ensures daily room maintenance and sparkling common areas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Journey Timeline -->
    <section class="timeline-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Our Growth</span>
                <h2 class="section-title">The Homely PG Journey</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="timeline-box">
                        <div class="timeline-item">
                            <div class="timeline-year">2023 — The Genesis</div>
                            <p class="timeline-text">Founded in Delhi with 10 verified PG properties aimed at resolving student housing issues near coaching hubs.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2024 — Multi-City Expansion</div>
                            <p class="timeline-text">Expanded operations into Mumbai &amp; Bengaluru, welcoming over 1,000 active residents to the Homely family.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2025 — Tech &amp; AI Integration</div>
                            <p class="timeline-text">Introduced MongoDB-powered backend, live chatbot support, and instant online room reservation system.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2026 — 5,000+ Happy Residents</div>
                            <p class="timeline-text">Serving 4 major metro cities with over 400+ verified properties and industry-leading satisfaction scores.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Banner -->
    <div class="container">
        <div class="cta-banner">
            <h2 class="cta-title">Ready to Find Your Next Home Away from Home?</h2>
            <p class="cta-desc">Browse our curated list of top-rated PG accommodations across Delhi, Mumbai, Bengaluru, and Hyderabad.</p>
            <a href="index.php" class="btn-gradient-primary">
                <i class="fas fa-arrow-right"></i> Find Your PG Now
            </a>
        </div>
    </div>

    <?php
    include "includes/signup_modal.php";
    include "includes/login_modal.php";
    include "includes/footer.php";
    ?>

</body>
</html>