<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Azza Bakery</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>

  <!-- Navbar -->
  <header class="navbar">
    <h1 class="logo">Azza Bakery</h1>
    <nav>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Menu</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </nav>
  </header>

  <!-- Hero -->
  <section class="hero">
  <div class="hero-container">
    
    <!-- TEXT -->
    <div class="hero-left">
      <h1>Fresh & Delicious Bakery</h1>
      <p>Handmade bread and pastries everyday</p>
      <button>Order Now</button>
    </div>

    <!-- IMAGE -->
    <div class="hero-right">
      <img src="img/bread.png" alt="Bakery">
    </div>

  </div>
</section>

  <!-- Menu Section -->
  <section class="menu">
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
<section class="contact">
  <h2>Visit Us</h2>
  <p>Jl. Bakery No. 123, Indonesia</p>
  <p>Open: 08.00 - 21.00</p>
</section>

<section class="about">
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
<!-- Footer -->
  <footer>
    <p>© 2026 Azza Bakery</p>
  </footer>

  <script src="script.js"></script>
</body>
</html>



/* RESET */
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
  background: linear-gradient(to right, #f6a94d, #f39c3d);
}

.logo {
  color: #8b0000;
  font-size: 28px;
  font-weight: bold;
}

.navbar ul {
  display: flex;
  list-style: none;
}

.navbar ul li {
  margin-left: 25px;
}

.navbar ul li a {
  text-decoration: none;
  color: #8b0000;
  font-weight: 600;
  transition: 0.3s;
}

.navbar ul li a:hover {
  color: #ff5500;
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
  width: 90%;
  margin: auto;
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
  background: #ff7a00;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}

.hero-left button:hover {
  transform: scale(1.05);
  background: #ff5500;
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
  background: radial-gradient(circle, #ffd8a8, transparent);
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
  background: #ffb347;
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
  background: #ffb347;
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
