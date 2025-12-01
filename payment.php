<?php
// payment_clean_ui.php (with header added)
$upi_id = 'piyush9997138814-1@okaxis';
$payee_name = 'Piyush Agrawal';

$amount = isset($_GET['amount']) ? $_GET['amount'] : '0';
$property_id = isset($_GET['property_id']) ? $_GET['property_id'] : '';

$upi_link = "upi://pay?pa=" . urlencode($upi_id)
  . "&pn=" . urlencode($payee_name)
  . "&tn=" . urlencode("Booking Payment for Property #$property_id")
  . "&am=" . urlencode($amount)
  . "&cu=INR";
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pay - Homely PG</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root {
      --bg: #f7fbfc;
      --card: #fff;
      --muted: #6b7280;
      --accent: #06b6d4;
      --accent-dark: #0596a6;
      --radius: 14px;
      --header-h: 72px;
      font-family: 'Inter', system-ui;
    }

    * {
      box-sizing: border-box
    }

    body {
      background: var(--bg);
      margin: 0;
      -webkit-font-smoothing: antialiased
    }

    /* HEADER FROM PREVIOUS FILE */
    .header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: var(--header-h);
      background: #fff;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      z-index: 1000;
    }

    .header-inner {
      max-width: 1200px;
      margin: 0 auto;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      color: inherit
    }

    .brand-logo {
      height: 40px;
      width: auto
    }

    .brand-text {
      font-weight: 600;
      color: var(--accent);
      font-size: 18px
    }

    .menu {
      display: flex;
      align-items: center;
      gap: 18px;
      font-size: 16px
    }

    .menu a {
      text-decoration: none;
      color: var(--muted);
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 6px
    }

    .menu a:hover {
      color: var(--accent)
    }

    .divider {
      color: #e6e6e6;
      margin: 0 6px;
      user-select: none
    }

    .menu-toggle {
      display: none;
      background: transparent;
      border: none;
      font-size: 20px;
      color: var(--muted)
    }

    @media(max-width:768px) {
      .menu {
        position: absolute;
        right: 16px;
        top: calc(var(--header-h) + 10px);
        flex-direction: column;
        background: #fff;
        padding: 8px;
        border-radius: 10px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        display: none
      }

      .menu.open {
        display: flex
      }

      .divider {
        display: none
      }

      .menu-toggle {
        display: block
      }
    }

    .header-spacer {
      height: var(--header-h)
    }

    /* PAYMENT UI */
    .page-wrap {
      padding: 20px;
      display: flex;
      justify-content: center
    }

    .payment-card {
      max-width: 520px;
      width: 96%;
      background: var(--card);
      border-radius: var(--radius);
      padding: 26px;
      box-shadow: 0 10px 30px rgba(16, 24, 40, 0.06)
    }

    .qr-wrap {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px
    }

    @media(max-width:680px) {
      .qr-wrap {
        grid-template-columns: 1fr
      }
    }

    .qr-img {
      width: 100%;
      border-radius: 12px;
      background: #eef7f9;
      padding: 18px;
      display: flex;
      justify-content: center
    }

    .qr-img img {
      max-width: 220px;
      width: 100%;
      aspect-ratio: 1/1;
      object-fit: contain;
      border-radius: 8px;
      background: #fff;
      padding: 10px
    }

    .info {
      display: flex;
      flex-direction: column;
      gap: 12px
    }

    .small {
      color: var(--muted);
      font-size: 0.92rem
    }

    .upi-id {
      font-weight: 600;
      word-break: break-all
    }

    .chip {
      background: #f3fbfc;
      color: var(--accent-dark);
      padding: 6px 10px;
      border-radius: 999px;
      font-weight: 600;
      font-size: 0.9rem
    }

    .actions .btn {
      border-radius: 12px
    }

    footer.note {
      text-align: center;
      margin-top: 14px;
      color: var(--muted);
      font-size: 0.85rem
    }
  </style>
</head>

<body>
  <!-- FIXED HEADER -->
  <header class="header">
    <div class="header-inner">
      <a class="brand" href="index.php">
        <img src="img/logo.png" class="brand-logo">
      </a>

      <button class="menu-toggle" onclick="toggleMenu()"><i class="fa fa-bars"></i></button>

      <nav class="menu" id="mainMenu">
        <a href="index.php"><i class="fa fa-home"></i> Home</a>
        <span class="divider">|</span>
        <a href="about.php"><i class="fa fa-info-circle"></i> About</a>
      </nav>
    </div>
  </header>

  <div class="header-spacer"></div>

  <main class="page-wrap">
    <div class="payment-card">
      <h2>Complete your booking</h2>
      <p style="color:var(--muted)">Pay securely using any UPI app.</p>

      <div class="qr-wrap">
        <div class="qr-img">
          <img src="img/payment.png" alt="UPI QR Code">
        </div>

        <div class="info">
          <div>
            <div class="small">Payee</div>
            <div class="upi-id"><?php echo htmlspecialchars($payee_name); ?></div>
          </div>

          <div>
            <div class="small">UPI ID</div>
            <div class="upi-id" id="upiText"><?php echo htmlspecialchars($upi_id); ?></div>
          </div>

          <div>
            <div class="small">Amount</div>
            <div class="upi-id">₹ <?php echo htmlspecialchars($amount); ?></div>
          </div>



          <div class="actions mt-3 d-grid gap-2">
            <a href="<?php echo htmlspecialchars($upi_link); ?>" class="btn btn-success btn-lg">Open UPI App</a>
            <a href="img/payment.png" download class="btn btn-outline-primary">Download QR</a>
            <button id="copyBtn" class="btn btn-outline-secondary">Copy UPI ID</button>
          </div>
        </div>
      </div>

      <footer class="note">Need help? <a href="contact.php">Contact us</a></footer>
    </div>
  </main>

  <script>
    function toggleMenu() {
      document.getElementById('mainMenu').classList.toggle('open');
    }

    window.addEventListener('click', function(e) {
      const m = document.getElementById('mainMenu');
      const t = document.querySelector('.menu-toggle');
      if (!m.contains(e.target) && !t.contains(e.target)) {
        m.classList.remove('open');
      }
    });

    document.getElementById('copyBtn').addEventListener('click', async function() {
      try {
        await navigator.clipboard.writeText("<?php echo $upi_id; ?>");
        this.textContent = "Copied ✓";
        setTimeout(() => this.textContent = "Copy UPI ID", 1200);
      } catch (err) {
        alert("Copy manually: <?php echo $upi_id; ?>");
      }
    });
  </script>

</body>

</html>

<!-- ===== Footer Start (Narrower Version) ===== -->
<footer class="site-footer">
  <div class="footer-container">

    <!-- City Links -->
    <div class="footer-top">
      <a class="city-link" href="property_list.php?city=Delhi">PG in Delhi</a>
      <a class="city-link" href="property_list.php?city=Mumbai">PG in Mumbai</a>
      <a class="city-link" href="property_list.php?city=Bengaluru">PG in Bangalore</a>
      <a class="city-link" href="property_list.php?city=Hyderabad">PG in Hyderabad</a>
    </div>

    <!-- Info -->
    <div class="footer-center">
      <p>© 2024 Copyright Homely PG</p>
      <p>Made by: <a href="https://www.linkedin.com/in/piyush-agrawal-b01249203/">Piyush Agrawal</a></p>
      <p><a href="https://www.ietlucknow.ac.in/">IET LUCKNOW</a></p>

      <div class="footer-links">
        <a href="index.php">Home</a>
        <a href="#" id="backToTop">Back to top</a>
      </div>
    </div>

  </div>
</footer>

<style>
  /* MAIN FOOTER BOX */
  .site-footer {
    background: #2f2f2f;
    color: #e8e8e8;
    padding: 32px 0 40px;
    text-align: center;
    border-top: 4px solid #444;
    font-family: Arial, sans-serif;
  }

  /* NARROWER CONTAINER */
  .footer-container {
    max-width: 700px;
    /* <<< CONTROL WIDTH HERE */
    margin: 0 auto;
    padding: 0 15px;
  }

  /* TOP CITY LINKS */
  .footer-top {
    display: flex;
    justify-content: space-between;
    /* evenly spaced but narrower */
    gap: 15px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    flex-wrap: wrap;
  }

  .city-link {
    color: #e6e6e6;
    text-decoration: none;
    font-size: 15px;
    white-space: nowrap;
  }

  .city-link:hover {
    color: #06b6d4;
  }

  /* CENTER TEXT */
  .footer-center {
    margin-top: 16px;
    line-height: 1.6;
  }

  .footer-center a {
    color: #06b6d4;
    text-decoration: none;
  }

  .footer-center a:hover {
    text-decoration: underline;
  }

  /* HOME + BACK TO TOP */
  .footer-links {
    margin-top: 10px;
    display: flex;
    justify-content: center;
    gap: 14px;
  }

  /* Responsive for mobile */
  @media (max-width: 500px) {
    .footer-top {
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }
  }
</style>

<script>
  document.getElementById('backToTop').addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
</script>