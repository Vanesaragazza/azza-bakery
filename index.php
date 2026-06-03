<?php
// kosong aja kalau belum ada PHP
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azza Bakery</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

    <style>
        /* RESET */
html{
  scroll-behavior: smooth;
}
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: #fff8f0;
}

/* NAVBAR */
.navbar {
  position: sticky;
  top: 0;
  z-index: 100;

  display: flex;
  justify-content: space-between;
  align-items: center;

  padding: 20px 60px;
  background: linear-gradient(to right, brown, #f39c3d);
}

.logo {
  color: #8b0000;
  font-size: 28px;
  font-weight: bold;
}

.logo-white{
    color: white;
}

.logo{
    color: darkred;
}

/* NAV */
nav {
  display: flex;
  align-items: center;
  gap: 25px;
}

/* MENU */
.navbar ul {
  display: flex;
  list-style: none;
  gap: 20px;
}

.navbar ul li a {
  text-decoration: none;
  color: #8b0000;
  font-weight: 600;
  transition: 0.3s;
}

.navbar ul li a:hover {
  color:  brown;
}

/* CART */
.cart-icon {
  background: brown;
  color: white;
  padding: 10px 18px;
  border-radius: 30px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.3s;
}

.cart-icon:hover {
  transform: scale(1.1);
}
/* HERO */
.hero {
  position: relative;
  height: 100vh;
  display: flex;
  align-items: center;
  overflow: hidden;
  background: #fff8f0;
}

.hero-container {
  width: 100%;
  padding-left: 5%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* TEXT */
.hero-left {
  max-width: 500px;
}

.hero-left h1 {
  font-size: 60px;
  color: #a52a2a;
  margin-bottom: 15px;
}

.hero-left p {
  font-size: 18px;
  color: #555;
  margin-bottom: 20px;
}

.hero-left button {
  padding: 12px 25px;
  background: brown;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}

.hero-left button:hover {
  transform: scale(1.05);
  background: brown;
}

/* IMAGE */
.hero-right img {
  width: 500px;
  z-index: 2;
}

/* BACKGROUND DECOR */
.hero::after {
  content: "";
  position: absolute;
  right: -100px;
  bottom: -50px;
  width: 600px;
  height: 600px;
  background: radial-gradient(circle,  transparent);
  z-index: 1;
}

/* MENU */
.menu {
  padding: 80px 50px;
  text-align: center;
}

.menu h2 {
  font-size: 30px;
  margin-bottom: 20px;
}

.menu-container {
  display: flex;
  justify-content: center;
  gap: 25px;
}

.card {
  background: white;
  padding: 20px;
  width: 220px;
  border-radius: 15px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  transition: 0.3s;
}

.card img {
  width: 100%;
  border-radius: 10px;
}

.card:hover {
  transform: translateY(-10px);
}

/* STORY */
.story {
  padding: 80px 50px;
  background: #fff5eb;
}

.story-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 50px;
}

.story-text {
  max-width: 500px;
}

.story-img img {
  width: 100%;
  border-radius: 15px;
}

/* POPULAR */
.popular {
  padding: 80px 50px;
  text-align: center;
}

.popular-container {
  display: flex;
  justify-content: center;
  gap: 25px;
}

.product {
  background: white;
  padding: 20px;
  width: 200px;
  border-radius: 15px;
  transition: 0.3s;
}

.product:hover {
  transform: translateY(-10px);
}

/* TESTIMONI */
.testimoni {
  padding: 80px 50px;
  text-align: center;
}

.testi-container {
  display: flex;
  justify-content: center;
  gap: 20px;
}

.testi-card {
  background: white;
  padding: 20px;
  width: 250px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* STATS */
.stats {
  display: flex;
  justify-content: center;
  gap: 50px;
  padding: 60px;
  background: brown;
  color: white;
  text-align: center;
}

/* CONTACT */
.contact {
  padding: 80px;
  text-align: center;
  background: #fff0e0;
}

/* ABOUT */
.about {
  padding: 80px;
  text-align: center;
}

/* FOOTER */
footer {
  background: brown;
  color: white;
  padding: 30px;
  text-align: center;
}

.about {
  padding: 80px 50px;
  background: #fff5eb;
}

.about-container {
  display: flex;
  align-items: center;
  gap: 50px;
}

/* GAMBAR */
.about-image {
  position: relative;
  flex: 1;
}

.about-image img {
  width: 100%;
  border-radius: 20px;
}

/* BADGE MERAH */
.badge {
  position: absolute;
  bottom: 20px;
  right: 20px;
  background: #a52a2a;
  color: white;
  padding: 20px;
  border-radius: 15px;
  text-align: center;
}

.badge h3 {
  font-size: 30px;
}

/* TEXT */
.about-content {
  flex: 1;
}

.about-content h2 {
  color: #a52a2a;
  font-size: 36px;
  margin-bottom: 15px;
}

.about-content p {
  margin-bottom: 15px;
  color: #555;
}

.about-content h3 {
  margin-top: 20px;
  color: #a52a2a;
}

/* LIST */
.about-content ul {
  margin-top: 15px;
  list-style: none;
}

.about-content ul li {
  margin-bottom: 10px;
  padding-left: 25px;
  position: relative;
}

/* ICON CEKLIS */
.about-content ul li::before {
  content: "✔";
  position: absolute;
  left: 0;
  color: #a52a2a;
}

/* CART */
.cart-icon {
  background: brown;
  color: white;
  padding: 10px 18px;
  border-radius: 30px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.3s;
}

.cart-icon:hover {
  transform: scale(1.1);
}

/* FAQ */
.faq {
  padding: 80px 50px;
  background: #fff8f0;
  text-align: center;
}

.faq h2 {
  font-size: 42px;
  color: #a52a2a;
  margin-bottom: 40px;
}

.faq-item {
  max-width: 800px;
  margin: 20px auto;
  background: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
  text-align: left;
}

.faq-item h3 {
  color: #a52a2a;
  margin-bottom: 10px;
}

.faq-item p {
  color: #555;
  line-height: 1.6;
}

#faq {
  scroll-margin-top: 120px;
}

.map-container {
  max-width: 900px;
  margin: 30px auto 0;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.map-container iframe {
  width: 100%;
  display: block;
}

.contact {
  padding: 80px 50px;
  background: #fff0e0;
}

.contact h2 {
  text-align: center;
  margin-bottom: 40px;
  color: #a52a2a;
}

.contact-container {
  display: flex;
  gap: 40px;
  align-items: center;
  justify-content: center;
}

.contact-map {
  flex: 1;
}

.contact-map iframe {
  width: 100%;
  border-radius: 15px;
}

.contact-info {
  flex: 1;
}

.contact-info h3 {
  color: #a52a2a;
  margin-bottom: 20px;
}

.contact-info p {
  margin-bottom: 15px;
  line-height: 1.8;
}

.contact {
  padding: 80px 50px;
  background: #fff0e0;
}

.contact h2 {
  text-align: center;
  color: #a52a2a;
  margin-bottom: 40px;
}

.contact-container {
  display: flex;
  gap: 40px;
  align-items: flex-start;
}

.contact-map {
  flex: 1.2;
}

.contact-map iframe {
  width: 100%;
  border-radius: 15px;
}

.contact-info {
  flex: 0.8;
  background: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.contact-info h3 {
  color: #a52a2a;
  margin-bottom: 15px;
}

.contact-info p {
  margin-bottom: 15px;
  line-height: 1.6;
}

.contact-info hr {
  margin: 20px 0;
  border: none;
  height: 1px;
  background: #ddd;
}
@media (max-width: 768px){

  .hero-container,
  .story-container,
  .about-container,
  .contact-container{
    flex-direction: column;
    text-align: center;
  }

  .hero-left h1{
    font-size: 40px;
  }

  .menu-container,
  .popular-container,
  .testi-container{
    flex-direction: column;
    align-items: center;
  }

  .stats{
    flex-direction: column;
    gap: 20px;
  }

  .navbar{
    flex-direction: column;
    gap: 15px;
  }

  .navbar ul{
    flex-wrap: wrap;
    justify-content: center;
  }
}
    </style>
</head>
<body>

    <!-- Navbar -->
<header class="navbar">

  <h1 class="logo">
   <span class="logo-white">Azza</span> Bakery
  </h1>

  <nav>

    <ul>
      <li><a href="#home">Home</a></li>
      <li><a href="#menu">Menu</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#faq">FAQ</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>

    <div class="cart-icon">
      🛒 <span id="cart-count">0</span>
    </div>

  </nav>

</header>

  <!-- Hero -->
  <!-- Hero -->
<section class="hero" id="home">
  <div class="hero-container">
    
    <!-- TEXT -->
    <div class="hero-left">
      <h1>Fresh & Delicious Bakery</h1>
      <p>Handmade bread and pastries everyday</p>
      <button>Order Now</button>
    </div>

    <!-- IMAGE -->
    <div class="hero-right">
      <img src="https://plus.unsplash.com/premium_photo-1665669263531-cdcbe18e7fe4?q=80&w=1225&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Bakery">
    </div>

  </div>
</section>

  <!-- Menu Section -->
  <!-- Menu -->
<section class="menu" id="menu">
    <h2>Our Menu</h2>
    <div class="menu-container">

      <div class="card">
        <img src="https://via.placeholder.com/200" alt="">
        <h3>Croissant</h3>
        <p>Rp 15.000</p>
      </div>

      <div class="card">
        <img src="https://via.placeholder.com/200" alt="">
        <h3>Donut</h3>
        <p>Rp 10.000</p>
      </div>

      <div class="card">
        <img src="https://via.placeholder.com/200" alt="">
        <h3>Bread</h3>
        <p>Rp 12.000</p>
      </div>

    </div>
  </section>

  

<!-- Testimoni -->
<section class="testimoni">
  <h2>What Our Customers Say</h2>
  <div class="testi-container">
    <div class="testi-card">
      <p>"Rotinya enak banget, fresh!"</p>
      <h4>- Andi</h4>
    </div>
    <div class="testi-card">
      <p>"Harga murah tapi kualitas premium."</p>
      <h4>- Sinta</h4>
    </div>
  </div>
</section>
<section class="story">
  <div class="story-container">
    <div class="story-text">
      <h2>Our Story</h2>
      <p>
        Azza Bakery berdiri sejak 2020 dengan tujuan menghadirkan roti fresh 
        dan berkualitas tinggi untuk semua kalangan. Kami menggunakan bahan 
        premium dan resep tradisional yang dipadukan dengan inovasi modern.
      </p>
    </div>
    <div class="story-img">
      <img src="https://via.placeholder.com/400x300" alt="">
    </div>
  </div>
</section>
<section class="popular">
  <h2>Popular Products</h2>
  <div class="popular-container">

    <div class="product">
      <img src="https://via.placeholder.com/200">
      <h3>Chocolate Cake</h3>
      <p>Best seller 🔥</p>
    </div>

    <div class="product">
      <img src="https://via.placeholder.com/200">
      <h3>Strawberry Donut</h3>
      <p>Sweet & fresh</p>
    </div>

    <div class="product">
      <img src="https://via.placeholder.com/200">
      <h3>Cheese Bread</h3>
      <p>Soft & cheesy</p>
    </div>

  </div>
</section>
<section class="stats">
  <div class="stat">
    <h2>500+</h2>
    <p>Customers Daily</p>
  </div>
  <div class="stat">
    <h2>50+</h2>
    <p>Menu Items</p>
  </div>
  <div class="stat">
    <h2>5★</h2>
    <p>Rating</p>
  </div>
</section>
<section class="contact" id="contact">
  <h2>Visit Us</h2>

  <div class="contact-container">

    <!-- KIRI -->
    <div class="contact-map">
      <iframe
  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1015140.5233996323!2d107.765619165625!3d-6.343164199999988!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6ebb445eb32f03%3A0x81a239f2fa2e1d92!2sAZZA%20BAKERY!5e0!3m2!1sid!2sid!4v1780386411802!5m2!1sid!2sid"
  width="100%"
  height="400"
  style="border:0;"
  allowfullscreen=""
  loading="lazy"
  referrerpolicy="no-referrer-when-downgrade">
</iframe>
    </div>

    <!-- KANAN -->
    <div class="contact-info">

      <h3>Contact Us</h3>

      <p><strong>📍 Alamat:</strong><br>
      Jl. Tj. Pura No.13, Kepandean, Indramayu</p>

      <p><strong>📞 Telepon:</strong><br>
      08xx-xxxx-xxxx</p>

      <p><strong>✉ Email:</strong><br>
      azzabakery@gmail.com</p>

      <hr>

      <h3>Customer Reviews</h3>

      <!-- Elfsight Google Reviews -->
      <script src="https://elfsightcdn.com/platform.js" async></script>

      <div
        class="elfsight-app-a9145307-df05-4c0a-b7fc-3f9f10837acc"
        data-elfsight-app-lazy>
      </div>

    </div>

  </div>
</section>

<!-- About -->
<section class="about-section" id="about">
  <div class="about-container">

    <!-- KIRI (GAMBAR) -->
    <div class="about-image">
      <img src="img/bakery.jpg" alt="Bakery">

      <div class="badge">
        <h3>5+</h3>
        <p>Tahun Pengalaman</p>
      </div>
    </div>

    <!-- KANAN (TEXT) -->
    <div class="about-content">
      <h2>Tentang AZZA Bakery</h2>

      <p>
        AZZA Bakery adalah toko roti dan kue yang didirikan dengan passion 
        untuk menghadirkan kelezatan homemade berkualitas premium.
      </p>

      <p>
        Kami percaya bahwa setiap gigitan harus menjadi pengalaman yang berkesan.
      </p>

      <h3>Visi & Misi Kami</h3>

      <ul>
        <li>Produk berkualitas tinggi</li>
        <li>Bahan selalu fresh</li>
        <li>Pelayanan terbaik</li>
        <li>Inovasi produk baru</li>
      </ul>

    </div>

  </div>
</section>


<!-- FAQ -->
<section class="faq" id="faq">

  <h2>Frequently Asked Questions</h2>

  <div class="faq-item">
    <h3>Apakah roti dibuat fresh setiap hari?</h3>
    <p>Ya, semua roti dan pastry kami dibuat fresh setiap hari.</p>
  </div>

  <div class="faq-item">
    <h3>Apakah bisa custom cake?</h3>
    <p>Tentu! Kami menerima pesanan custom cake sesuai keinginan pelanggan.</p>
  </div>

  <div class="faq-item">
    <h3>Apakah tersedia delivery?</h3>
    <p>Ya, kami melayani pengiriman melalui ojek online dan kurir lokal.</p>
  </div>

</section>


<!-- Footer -->
  <footer>
    <p>© 2026 Azza Bakery</p>
  </footer>

    <script>
        // Navbar shadow pas scroll
window.addEventListener("scroll", () => {
  const navbar = document.querySelector(".navbar");
  navbar.classList.toggle("scrolled", window.scrollY > 50);
});

// Fade-in saat scroll
const faders = document.querySelectorAll(
  ".card, .about-section, .testi-card"
);

const appearOnScroll = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add("show");
    }
  });
});

faders.forEach(el => {
  el.classList.add("fade");
  appearOnScroll.observe(el);
});


    </script>

</body>
</html>