<?php
// contact.php
session_start();           
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<header class="header">
  <div class="header-inner">
    <a class="brand" href="index.php">
      <img src="img/logo.png" alt="Homely PG logo" class="brand-logo">
      <span class="brand-text">Homely PG</span>
    </a>

    <button class="menu-toggle" aria-label="Toggle menu" onclick="toggleMenu()">
      <i class="fa fa-bars"></i>
    </button>

    <nav class="menu" id="mainMenu">
      <a href="index.php"><i class="fa fa-home"></i> Home</a>
      <span class="divider">|</span>
      <a href="about.php"><i class="fa fa-info-circle"></i> About</a>
    </nav>
  </div>
</header>

<!-- add a spacer so page content isn't hidden under the fixed header -->
<div class="header-spacer" aria-hidden="true"></div>

<style>
/* --- header layout --- */
:root{
  --header-bg: #fff;
  --text-muted: #6b6b6b;
  --accent: #06b6d4;
  --header-height: 72px; /* header height used for spacer */
}

*{box-sizing:border-box}

.header{
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background: var(--header-bg);
  z-index: 1000;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

/* keeps header content centered and with max width */
.header-inner{
  max-width: 1200px;
  margin: 0 auto;
  height: var(--header-height);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  gap: 20px;
}

/* brand / logo */
.brand{
  display:flex;
  align-items:center;
  gap:10px;
  text-decoration:none;
  color: inherit;
}
.brand-logo{
  height: 40px;     /* fixed visual height */
  width: auto;      /* keep aspect ratio */
  display:block;
}
.brand-text{
  font-weight:600;
  color: var(--accent);
  font-size: 18px;
  display: none; /* hide text on very small screens; show via media query */
}

/* Menu */
.menu{
  display:flex;
  align-items:center;
  gap:18px;
  font-size:16px;
}
.menu a{
  text-decoration:none;
  color: var(--text-muted);
  display:flex;
  align-items:center;
  gap:8px;
  padding: 8px 6px;
}
.menu a:hover{ color: var(--accent); }

.divider{
  color: #e6e6e6;
  margin: 0 6px;
  user-select: none;
}

/* hamburger toggle (hidden on desktop) */
.menu-toggle{
  display:none;
  background: transparent;
  border: none;
  font-size: 20px;
  cursor: pointer;
  color: var(--text-muted);
}

/* spacer so content below header is visible */
.header-spacer{
  height: var(--header-height);
}

/* ---------- Responsive rules ---------- */
@media (max-width: 1024px){
  .header-inner{ padding: 0 16px; }
  .brand-text{ display: inline-block; font-size: 16px; }
}

@media (max-width: 768px){
  .brand-text{ display: none; } /* keep only logo on small screens */

  .menu{
    position: absolute;
    right: 16px;
    top: calc(var(--header-height) + 8px);
    flex-direction: column;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    padding: 8px;
    display: none; /* hidden by default on mobile */
    min-width: 160px;
  }

  .menu.open{ display: flex; }

  .menu a{ padding: 10px 12px; white-space: nowrap; }

  .divider{ display:none; }

  .menu-toggle{ display: inline-block; }
}

/* optional: prevent long nav links from wrapping weirdly */
.menu a i { min-width: 16px; text-align:center; }
</style>

<script>
function toggleMenu(){
  const m = document.getElementById('mainMenu');
  m.classList.toggle('open');
}
/* close menu if click outside on small screens */
document.addEventListener('click', function(e){
  const menu = document.getElementById('mainMenu');
  const toggle = document.querySelector('.menu-toggle');
  if (!menu || !toggle) return;
  const isClickInside = menu.contains(e.target) || toggle.contains(e.target);
  if (!isClickInside && menu.classList.contains('open')){
    menu.classList.remove('open');
  }
});
</script>

<style>
    .contact-container{
        max-width: 900px;
        margin: 40px auto;
        padding: 20px;
    }
    .contact-title{
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 10px;
        text-align:center;
    }
    .contact-sub{
        color:#555;
        text-align:center;
        margin-bottom:30px;
    }
    .contact-box{
        background:white;
        padding:25px;
        border-radius:10px;
        box-shadow:0 0 10px rgba(0,0,0,0.1);
    }
    .form-group{
        margin-bottom:15px;
    }
    .form-group label{
        font-weight:600;
        display:block;
        margin-bottom:5px;
    }
    .form-group input, 
    .form-group textarea{
        width:100%;
        border:1px solid #ccc;
        padding:10px;
        border-radius:6px;
        font-size:15px;
    }
    .form-group textarea{
        height:120px;
        resize:none;
    }
    .submit-btn{
        background:#0077ff;
        color:white;
        border:none;
        padding:12px 20px;
        border-radius:6px;
        font-size:16px;
        cursor:pointer;
        transition:0.3s;
        width:100%;
    }
    .submit-btn:hover{
        background:#005fcc;
    }

    .contact-info{
        margin-top:35px;
        padding:20px;
        background:#f7f7f7;
        border-radius:10px;
    }
    .contact-info h3{
        margin-bottom:10px;
    }
    .contact-info p{
        margin:5px 0;
        font-size:15px;
    }
</style>


<div class="contact-container" >
    <div class="contact-title">Get in Touch</div>
    <div class="contact-sub">We'd love to hear from you! Fill out the form below.</div>

    <div class="contact-box">
        <form action="send_message.php" method="POST">
            <div class="form-group">
                <label>Your Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Your Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Your Message</label>
                <textarea name="message" required></textarea>
            </div>

            <button class="submit-btn" style="hover" type="submit">Send Message</button>
        </form>
    </div>

    <div class="contact-info">
        <h3>Contact Information</h3>
        <p><strong>Email:</strong> support_homely_pg@gmail.com</p>
        <p><strong>Phone:</strong> +91 9105610746</p>
        <p><strong>Address:</strong> Lucknow, Uttar Pradesh, India</p>
    </div>
</div>

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
  max-width: 700px;       /* <<< CONTROL WIDTH HERE */
  margin: 0 auto;
  padding: 0 15px;
}

/* TOP CITY LINKS */
.footer-top {
  display: flex;
  justify-content: space-between;  /* evenly spaced but narrower */
  gap: 15px;
  padding-bottom: 12px;
  border-bottom: 1px solid rgba(255,255,255,0.08);
  flex-wrap: wrap;
}

.city-link {
  color: #e6e6e6;
  text-decoration: none;
  font-size: 15px;
  white-space: nowrap;
}
.city-link:hover { color: #06b6d4; }

/* CENTER TEXT */
.footer-center {
  margin-top: 10px;
  line-height: 1.6;
}

.footer-center a {
  color: #06b6d4;
  text-decoration: none;
}
.footer-center a:hover { text-decoration: underline; }

/* HOME + BACK TO TOP */
.footer-links {
  margin-top: 0px;
  display: flex;
  justify-content: center;
}

/* Responsive for mobile */
@media (max-width: 500px) {
  .footer-top {
    flex-direction: column;
    align-items: center;
  }
}
</style>

<script>
document.getElementById('backToTop').addEventListener('click', function(e){
  e.preventDefault();
  window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>



