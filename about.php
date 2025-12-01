<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Homely PG — About</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Google font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --accent: #06b6d4;
      --bg: #f6fafb;
      --card: #ffffff;
      --muted: #68707a;
      --radius: 12px;
      --header-height: 72px;
      --max-width: 1200px;
    }

    /* Reset / base */
    * {
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, Arial;
      color: #1f2937;
      background: var(--bg);
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    img {
      max-width: 100%;
      height: auto;
      display: block;
    }

    /* ===== Header (sticky) ===== */
    .header {
      position: fixed;
      inset: 0 0 auto 0;
      /* top, right, bottom, left shorthand (top:0) */
      top: 0;
      left: 0;
      right: 0;
      height: var(--header-height);
      background: #fff;
      z-index: 1100;
      box-shadow: 0 2px 8px rgba(9, 10, 10, 0.05);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .header-inner {
      width: 100%;
      max-width: var(--max-width);
      padding: 0 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      color: inherit;
    }

    .brand-logo {
      height: 42px;
      width: auto;
      display: block;
    }

    .brand-text {
      font-weight: 700;
      color: var(--accent);
      font-size: 18px;
      display: none;
    }

    /* nav */
    nav.menu {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 15px;
    }

    nav.menu a {
      color: var(--muted);
      text-decoration: none;
      display: flex;
      gap: 8px;
      align-items: center;
      padding: 8px 10px;
      border-radius: 8px;
    }

    nav.menu a:hover {
      color: var(--accent);
      background: rgba(6, 182, 212, 0.04);
    }

    .divider {
      color: #e6e6e6;
      padding: 0 6px;
      user-select: none;
    }

    /* hamburger toggle */
    .menu-toggle {
      display: none;
      background: transparent;
      border: 0;
      font-size: 20px;
      cursor: pointer;
      color: var(--muted);
      padding: 8px;
      border-radius: 8px;
    }

    /* spacer so body content not hidden under header */
    .header-spacer {
      height: var(--header-height);
      width: 100%;
    }

    /* ===== Page container / hero / card ===== */
    .container {
      max-width: var(--max-width);
      margin: 0 auto;
      padding: 0 20px;
    }

    .about-hero {
      padding: 40px 0 36px;
      background: linear-gradient(180deg, rgba(6, 182, 212, 0.03), rgba(6, 182, 212, 0.01));
    }

    .hero-grid {
      display: grid;
      gap: 20px;
      grid-template-columns: 1fr;
      align-items: center;
    }

    @media(min-width:900px) {
      .hero-grid {
        grid-template-columns: 1fr 420px;
      }
    }

    .hero-card {
      background: linear-gradient(180deg, #fff, #fbfeff);
      border-radius: var(--radius);
      padding: 22px;
      box-shadow: 0 6px 28px rgba(18, 38, 63, 0.06);
    }

    .kicker {
      display: inline-block;
      font-weight: 600;
      color: var(--accent);
      font-size: 13px;
      letter-spacing: 0.6px;
      background: rgba(6, 182, 212, 0.06);
      padding: 6px 10px;
      border-radius: 999px;
    }

    .lead {
      color: var(--muted);
      margin: 6px 0 12px;
      font-size: 16px;
      line-height: 1.6;
    }

    .stats {
      display: flex;
      gap: 12px;
      margin-top: 12px;
      flex-wrap: wrap;
    }

    .stat {
      background: rgba(15, 23, 42, 0.02);
      padding: 10px 12px;
      border-radius: 10px;
      text-align: center;
      min-width: 84px;
    }

    .stat strong {
      display: block;
      font-size: 18px;
    }

    /* Features grid */
    .features {
      display: grid;
      gap: 18px;
      margin: 28px 0;
      grid-template-columns: 1fr;
    }

    @media(min-width:720px) {
      .features {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    .feature-card {
      background: var(--card);
      padding: 16px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(18, 38, 63, 0.06);
      display: flex;
      gap: 12px;
      align-items: flex-start;
    }

    .feature-card img {
      width: 44px;
      height: 44px;
      object-fit: cover;
      border-radius: 8px;
    }

    /* Team */
    .team {
      display: grid;
      gap: 18px;
      grid-template-columns: 1fr;
      margin-bottom: 12px;
    }

    @media(min-width:720px) {
      .team {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    .member {
      background: var(--card);
      padding: 16px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 6px 18px rgba(18, 38, 63, 0.06);
    }

    .member img {
      width: 84px;
      height: 84px;
      object-fit: cover;
      border-radius: 999px;
      margin: 0 auto 10px;
    }

    /* CTA */
    .cta {
      margin: 24px 0;
      background: linear-gradient(90deg, rgba(6, 182, 212, 0.06), rgba(99, 102, 241, 0.04));
      padding: 18px;
      border-radius: 12px;
      display: flex;
      gap: 12px;
      align-items: center;
      justify-content: space-between;
      flex-direction: column;
    }

    @media(min-width:720px) {
      .cta {
        flex-direction: row;
      }
    }

    .btn {
      background: var(--accent);
      color: #fff;
      padding: 10px 16px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
      box-shadow: 0 8px 20px rgba(6, 182, 212, 0.12);
    }

    /* content card style used earlier (about-section) */
    .about-section {
      max-width: 1100px;
      margin: 24px auto;
      background: var(--card);
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }

    /* footer */
    .site-footer {
      background: #212225;
      color: #e8e8e8;
      padding: 32px 0;
      border-top: 4px solid #2b2b2b;
    }

    .footer-container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 0 16px;
      display: flex;
      gap: 20px;
      align-items: flex-start;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .footer-top {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }

    .city-link {
      color: #e6e6e6;
      text-decoration: none;
      font-size: 15px;
      padding: 6px 8px;
      border-radius: 8px;
      background: transparent;
    }

    .city-link:hover {
      color: var(--accent);
      background: rgba(6, 182, 212, 0.03);
    }

    .footer-center {
      text-align: left;
      min-width: 220px;
    }

    .footer-center a {
      color: var(--accent);
      text-decoration: none;
    }

    .footer-links {
      margin-top: 8px;
      display: flex;
      gap: 12px;
      align-items: center;
    }

    /* small screens behavior */
    @media (max-width: 1024px) {
      .brand-text {
        display: inline-block;
        font-size: 15px;
      }
    }

    @media (max-width: 768px) {
      .menu-toggle {
        display: inline-flex;
      }

      nav.menu {
        position: absolute;
        right: 12px;
        top: calc(var(--header-height) + 10px);
        flex-direction: column;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        padding: 8px;
        display: none;
        min-width: 180px;
        z-index: 1200;
      }

      nav.menu.open {
        display: flex;
      }

      nav.menu a {
        padding: 10px 12px;
        white-space: nowrap;
      }

      .divider {
        display: none;
      }

      /* hero spacing */
      .about-hero {
        padding-top: 24px;
      }

      .hero-card {
        padding: 18px;
      }

      .cta {
        padding: 14px;
      }

      .footer-container {
        padding: 0 12px;
        justify-content: center;
        text-align: center;
      }

      .footer-center {
        text-align: center;
        min-width: 0;
      }
    }

    @media (max-width: 420px) {
      .brand-logo {
        height: 36px;
      }

      .brand-text {
        display: none;
      }

      .stat {
        min-width: 72px;
        padding: 8px;
      }

      .feature-card {
        padding: 12px;
      }

      .member img {
        width: 72px;
        height: 72px;
      }

      .about-section {
        margin: 14px;
        padding: 16px;
      }
    }

    /* utility */
    .muted {
      color: var(--muted);
    }

    .spacer {
      height: 28px;
    }

    a:focus {
      outline: 3px solid rgba(6, 182, 212, 0.14);
      outline-offset: 2px;
      border-radius: 6px;
    }
  </style>
</head>

<body>
  <!-- HEADER -->
  <header class="header" role="banner">
    <div class="header-inner container">
      <a class="brand" href="index.php">
        <img src="img/logo.png" alt="Homely PG logo" class="brand-logo">
        <span class="brand-text">Homely PG</span>
      </a>

      <button class="menu-toggle" aria-expanded="false" aria-controls="mainMenu" id="menuToggle">
        <i class="fa fa-bars" aria-hidden="true"></i>
        <span class="sr-only" style="position:absolute;left:-9999px;">Toggle menu</span>
      </button>

      <nav class="menu" id="mainMenu" role="navigation" aria-label="Main navigation">
        <a href="index.php"><i class="fa fa-home" aria-hidden="true"></i><span>Home</span></a>
        <span class="divider" aria-hidden="true">|</span>
        <a href="about.php"><i class="fa fa-info-circle" aria-hidden="true"></i><span>About</span></a>
        <span class="divider" aria-hidden="true">|</span>
        <a href="contact.php"><i class="fa fa-phone" aria-hidden="true"></i><span>Contact</span></a>
      </nav>
    </div>
  </header>

  <!-- spacer so content not hidden -->
  <div class="header-spacer" aria-hidden="true"></div>

  <!-- ABOUT / HERO -->
  <main>
    <section class="about-hero">
      <div class="container">
        <div class="hero-grid">
          <div class="hero-card" role="region" aria-label="About Homely PG">
            <span class="kicker">Trusted</span>
            <h1 style="margin:10px 0 6px; font-size:22px;">Find comfortable, safe, and affordable PGs — fast</h1>
            <p class="lead">Homely PG connects students and professionals with verified PG accommodations across cities. Enjoy detailed listings, amenities filters, and trusted reviews.</p>

            <div class="stats" aria-hidden="true">
              <div class="stat"><strong>10+</strong><small class="muted">Verified PGs</small></div>
              <div class="stat"><strong>4</strong><small class="muted">Cities</small></div>
              <div class="stat"><strong>4.6</strong><small class="muted">Avg Rating</small></div>
            </div>
          </div>

          <aside>
            <div style="background:var(--card); padding:16px; border-radius:12px; box-shadow:0 6px 18px rgba(18,38,63,0.06);">
              <h4 style="margin:0 0 8px;">Quick Links</h4>
              <ul style="list-style:none; padding:0; margin:0;">
                <li style="margin:6px 0;"><a href="property_list.php?city=Delhi" class="muted">Browse Delhi PGs →</a></li>
                <li style="margin:6px 0;"><a href="property_list.php?city=Mumbai" class="muted">Browse Mumbai PGs →</a></li>
                <li style="margin:6px 0;"><a href="index.php" class="muted">Back to Home →</a></li>
              </ul>
            </div>
          </aside>
        </div>

        <!-- Features -->
        <div class="features" aria-label="Features">
          <div class="feature-card">
            <img src="img/logo1.webp" alt="" width="44" height="44">
            <div>
              <h4 style="margin:0 0 6px;">Verified Listings</h4>
              <p style="margin:0;" class="muted">Every property is verified before it shows on Homely PG, ensuring accurate info and real photos.</p>
            </div>
          </div>

          <div class="feature-card">
            <img src="img/logo1.webp" alt="">
            <div>
              <h4 style="margin:0 0 6px;">Smart Filters</h4>
              <p style="margin:0;" class="muted">Filter by amenities, rent, gender-specific PGs, and distance to campus or workplace.</p>
            </div>
          </div>

          <div class="feature-card">
            <img src="img/logo1.webp" alt="">
            <div>
              <h4 style="margin:0 0 6px;"><a href="contact.php">Local Support</a></h4>
              <p style="margin:0;" class="muted">Need help? Our support team assists with bookings and clarifies owner policies.</p>
            </div>
          </div>
        </div>

        <!-- About content card -->
        <div class="about-section" role="article" aria-label="About section">
          <h2 style="color:var(--accent);">About Homely PG</h2>
          <p>Homely PG is a user-friendly platform designed to help students, professionals, and travelers find comfortable and affordable Paying Guest accommodations in major cities.</p>
          <p>Our mission is to make finding a PG easier by offering verified listings, detailed property information, photos, amenities, and trusted user reviews.</p>

          <h3 style="color:var(--accent); margin-top:18px;">Why Choose Homely PG?</h3>
          <ul>
            <li>✔ Verified & trusted PG listings</li>
            <li>✔ High-quality photos & real reviews</li>
            <li>✔ Easy search and smart filters</li>
            <li>✔ Gender-specific and budget-friendly PGs</li>
            <li>✔ Clean, fast and responsive UI</li>
          </ul>
        </div>

        <!-- Team -->
        <h2 style="margin-top:22px;">Meet Developer</h2>
        <div class="team">
          <div class="member">
            <a href="https://www.linkedin.com/in/piyush-agrawal-b01249203/" style="text-decoration:none;color:inherit;">
              <img src="img/team1.jpg" alt="Piyush Agrawal">
              <h5 style="margin:8px 0 4px;">Piyush Agrawal</h5>
            </a>
            <p class="muted">Fullstack Development</p>
          </div>
        </div>

        <!-- CTA -->
        <div class="cta" role="region" aria-label="Call to action">
          <div>
            <h3 style="margin:0;">Want to list your PG or partner with us?</h3>
            <p class="muted" style="margin:6px 0 0;">We provide simple onboarding for owners and trusted payment options for tenants.</p>
          </div>
          <div style="display:flex; gap:10px; margin-top:8px;">
            <a class="btn" href="contact.php">Get in touch</a>
          </div>
        </div>

      </div>
    </section>
  </main>

  <div class="spacer"></div>

  <!-- FOOTER -->
  <footer class="site-footer" role="contentinfo">
    <div class="footer-container">
      <div class="footer-top" aria-hidden="true">
        <a class="city-link" href="property_list.php?city=Delhi">PG in Delhi</a>
        <a class="city-link" href="property_list.php?city=Mumbai">PG in Mumbai</a>
        <a class="city-link" href="property_list.php?city=Bengaluru">PG in Bengaluru</a>
        <a class="city-link" href="property_list.php?city=Hyderabad">PG in Hyderabad</a>
      </div>

      <div class="footer-center">
        <p>© 2024 Homely PG</p>
        <p>Made by: <a href="https://www.linkedin.com/in/piyush-agrawal-b01249203/">Piyush Agrawal</a></p>
        <div class="footer-links">
          <a href="index.php">Home</a>
          <a href="#" id="backToTop">Back to top</a>
        </div>
      </div>
    </div>
  </footer>

  <script>
    // Toggle menu for mobile
    (function() {
      const menu = document.getElementById('mainMenu');
      const toggle = document.getElementById('menuToggle');

      function setAria(open) {
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      }

      toggle.addEventListener('click', function(e) {
        const isOpen = menu.classList.toggle('open');
        setAria(isOpen);
      });

      // close menu on click outside (mobile)
      document.addEventListener('click', function(e) {
        const clickInside = menu.contains(e.target) || toggle.contains(e.target);
        if (!clickInside && menu.classList.contains('open')) {
          menu.classList.remove('open');
          setAria(false);
        }
      });

      // close mobile menu on orientation/resize to avoid stuck open state
      let resizeTimer;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
          if (window.innerWidth > 768 && menu.classList.contains('open')) {
            menu.classList.remove('open');
            setAria(false);
          }
        }, 120);
      });

      // Back to top smooth scroll
      document.getElementById('backToTop').addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });

      // Improve keyboard accessibility: close menu on Escape
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && menu.classList.contains('open')) {
          menu.classList.remove('open');
          setAria(false);
          toggle.focus();
        }
      });
    })();
  </script>
</body>

</html>