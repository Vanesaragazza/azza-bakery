<?php
// Memulai session — wajib ada di paling atas
// Session ini yang membuat PHP "ingat" siapa yang sudah login
session_start();

// Panggil file koneksi database
// ".." artinya naik satu folder ke atas (dari admin/ ke azza_bakery/)
require_once "../config/koneksi.php";

// Variabel untuk menyimpan pesan error jika login gagal
$error = "";

// Cek apakah form sudah dikirim (tombol Submit diklik)
// $_POST['submit'] berisi data yang dikirim dari form
if (isset($_POST['submit'])) {

    // Ambil username dan password yang diketik admin
    // trim() untuk menghapus spasi di awal/akhir
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Cari data di tabel users yang cocok dengan username & password
    // MD5() mengenkripsi password agar aman (sama seperti saat kita INSERT user)
    $query = "SELECT * FROM users WHERE username='$username' AND password=MD5('$password')";
    $result = mysqli_query($koneksi, $query);

    // Cek apakah ada data yang ditemukan
    if (mysqli_num_rows($result) == 1) {

        // Login BERHASIL — simpan username ke session
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username'];
        $_SESSION['id_user']  = $row['id_user'];

        // Arahkan admin ke halaman dashboard
        header("Location: dashboard.php");
        exit();

    } else {
        // Login GAGAL — tampilkan pesan error
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin — Azza Bakery</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --maroon:        #6B0F1A;
      --maroon-dark:   #4A0A12;
      --maroon-deep:   #2D0608;
      --maroon-mid:    #8B1A28;
      --maroon-light:  #B5293D;
      --gold:          #D4A847;
      --gold-light:    #E8C46A;
      --gold-glow:     rgba(212, 168, 71, 0.4);
      --glass-bg:      rgba(45, 6, 8, 0.45);
      --glass-border:  rgba(212, 168, 71, 0.3);
      --text-light:    #FFF5F5;
      --text-muted:    rgba(255, 235, 235, 0.6);
    }
 
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
 
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      font-family: 'DM Sans', sans-serif;
      overflow: hidden;
    }
 
    /* ── BACKGROUND — foto roti nyata dari Unsplash ── */
    .bg-photo {
      position: fixed;
      inset: 0;
      z-index: 0;
      /* Foto croissant & pastry hangat dari Unsplash (free license) */
      background-image: url('../uploads/bg-login.jpg');
      background-size: cover;
      background-position: center;
      /* Sedikit zoom out agar komposisi lebih lega */
      transform: scale(1.05);
      animation: slowZoom 20s ease-in-out infinite alternate;
    }
 
    /* Efek zoom halus supaya background terasa hidup */
    @keyframes slowZoom {
      from { transform: scale(1.05); }
      to   { transform: scale(1.12); }
    }
 
    /* Overlay gradien maroon dari kanan — agar card terbaca jelas */
    .bg-overlay {
      position: fixed;
      inset: 0;
      z-index: 1;
      background:
        linear-gradient(to right,
          rgba(45, 6, 8, 0.15) 0%,
          rgba(45, 6, 8, 0.55) 45%,
          rgba(45, 6, 8, 0.90) 75%,
          rgba(45, 6, 8, 0.97) 100%
        ),
        linear-gradient(to bottom,
          rgba(45, 6, 8, 0.5) 0%,
          transparent 30%,
          transparent 70%,
          rgba(45, 6, 8, 0.5) 100%
        );
    }
 
    /* ── KONTEN UTAMA ── */
    .page-content {
      position: relative;
      z-index: 10;
      width: 100%;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      padding: 40px 6% 40px 0;
    }
 
    /* ── BRAND pojok kiri atas ── */
    .brand-corner {
      position: fixed;
      top: 32px; left: 44px;
      z-index: 20;
      animation: fadeIn .8s ease .2s both;
    }
    .brand-name {
      font-family: 'Cormorant Garamond', serif;
      font-size: 26px;
      font-weight: 600;
      color: var(--text-light);
      letter-spacing: 1.5px;
    }
    .brand-name span { color: var(--gold); }
    .brand-tagline {
      font-size: 10px;
      font-weight: 300;
      color: var(--text-muted);
      letter-spacing: 3.5px;
      text-transform: uppercase;
      margin-top: 3px;
    }
 
    /* Garis ornamen bawah brand */
    .brand-line {
      width: 40px;
      height: 1.5px;
      background: linear-gradient(to right, var(--gold), transparent);
      margin-top: 8px;
    }
 
    /* ── TEKS KIRI BAWAH ── */
    .hero-text {
      position: fixed;
      left: 5%; bottom: 10%;
      z-index: 20;
      max-width: 420px;
      animation: fadeUp .9s ease .5s both;
    }
    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 10px;
      font-weight: 500;
      color: var(--gold);
      letter-spacing: 3px;
      text-transform: uppercase;
      margin-bottom: 14px;
    }
    .hero-badge::before {
      content: '';
      width: 28px; height: 1px;
      background: var(--gold);
      flex-shrink: 0;
    }
    .hero-text h1 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 58px;
      font-weight: 600;
      color: var(--text-light);
      line-height: 1.05;
      margin-bottom: 18px;
    }
    .hero-text h1 em {
      font-style: italic;
      color: var(--gold-light);
    }
    .hero-text p {
      font-size: 14px;
      font-weight: 300;
      color: var(--text-muted);
      line-height: 1.75;
      max-width: 320px;
    }
 
    /* Tiga titik dekoratif */
    .dots {
      display: flex;
      gap: 6px;
      margin-top: 24px;
    }
    .dots span {
      width: 6px; height: 6px;
      border-radius: 50%;
      background: var(--maroon-light);
    }
    .dots span:first-child { background: var(--gold); }
 
    /* ── GLASSMORPHISM CARD ── */
    .login-wrap {
      width: 400px;
      flex-shrink: 0;
      animation: slideIn .8s cubic-bezier(.16,1,.3,1) .3s both;
    }
 
    @keyframes slideIn {
      from { opacity:0; transform: translateX(50px); }
      to   { opacity:1; transform: translateX(0); }
    }
 
    .login-card {
      background: var(--glass-bg);
      backdrop-filter: blur(28px) saturate(1.6);
      -webkit-backdrop-filter: blur(28px) saturate(1.6);
      border: 1px solid var(--glass-border);
      border-radius: 28px;
      padding: 48px 44px;
      box-shadow:
        0 0 0 1px rgba(212,168,71,.08) inset,
        0 32px 64px rgba(45,6,8,.6),
        0 8px 24px rgba(45,6,8,.4);
    }
 
    /* Garis emas tipis di atas card */
    .login-card::before {
      content: '';
      display: block;
      width: 48px; height: 2px;
      background: linear-gradient(to right, var(--gold), transparent);
      margin-bottom: 28px;
      border-radius: 2px;
    }
 
    .card-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 34px;
      font-weight: 600;
      color: var(--text-light);
      line-height: 1.1;
      margin-bottom: 4px;
    }
    .card-sub {
      font-size: 13px;
      font-weight: 300;
      color: var(--text-muted);
      margin-bottom: 36px;
    }
 
    /* Error box */
    .error-box {
      background: rgba(139,26,40,.35);
      border: 1px solid rgba(181,41,61,.5);
      border-left: 3px solid var(--maroon-light);
      border-radius: 10px;
      padding: 12px 16px;
      margin-bottom: 24px;
      font-size: 13px;
      color: #FFCCD0;
      animation: shake .35s ease;
    }
    @keyframes shake {
      0%,100%{transform:translateX(0)}
      20%{transform:translateX(-7px)}
      60%{transform:translateX(7px)}
    }
 
    /* Input */
    .form-group { margin-bottom: 22px; }
    .form-group label {
      display: block;
      font-size: 10.5px;
      font-weight: 500;
      color: var(--text-muted);
      letter-spacing: 2.5px;
      text-transform: uppercase;
      margin-bottom: 9px;
    }
    .input-wrap { position: relative; }
    .form-group input {
      width: 100%;
      padding: 14px 18px;
      background: rgba(255,235,235,.07);
      border: 1px solid rgba(212,168,71,.2);
      border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: 15px;
      color: var(--text-light);
      outline: none;
      transition: border-color .25s, background .25s, box-shadow .25s;
    }
    .form-group input::placeholder { color: rgba(255,235,235,.25); }
    .form-group input:focus {
      border-color: var(--gold);
      background: rgba(212,168,71,.1);
      box-shadow: 0 0 0 4px rgba(212,168,71,.15);
    }
 
    /* Tombol login */
    .btn-login {
      width: 100%;
      padding: 15px;
      margin-top: 6px;
      background: linear-gradient(135deg, var(--maroon-light) 0%, var(--maroon) 50%, var(--maroon-dark) 100%);
      color: #FFE8EA;
      font-family: 'DM Sans', sans-serif;
      font-size: 15px;
      font-weight: 500;
      letter-spacing: .5px;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: transform .15s, box-shadow .15s, filter .15s;
      box-shadow: 0 6px 24px rgba(107,15,26,.5);
      position: relative;
      overflow: hidden;
    }
    /* Efek kilap di tombol */
    .btn-login::after {
      content: '';
      position: absolute;
      top: 0; left: -100%;
      width: 60%; height: 100%;
      background: linear-gradient(to right, transparent, rgba(255,255,255,.12), transparent);
      transform: skewX(-20deg);
      transition: left .5s ease;
    }
    .btn-login:hover::after { left: 160%; }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 32px rgba(107,15,26,.55);
      filter: brightness(1.08);
    }
    .btn-login:active { transform: translateY(0); }
 
    /* Divider */
    .divider {
      display: flex;
      align-items: center;
      gap: 14px;
      margin: 30px 0 0;
    }
    .div-line { flex:1; height:1px; background:rgba(212,168,71,.18); }
    .div-text {
      font-size: 11px;
      color: rgba(212,168,71,.5);
      letter-spacing: 1.5px;
      white-space: nowrap;
    }
 
    .card-footer {
      text-align: center;
      margin-top: 18px;
      font-size: 11.5px;
      color: rgba(255,235,235,.25);
    }
 
    /* Footer halaman */
    .page-footer {
      position: fixed;
      bottom: 28px; right: 44px;
      z-index: 20;
      font-size: 10.5px;
      color: rgba(255,235,235,.2);
      letter-spacing: 1.5px;
    }
 
    /* Animasi */
    @keyframes fadeIn { from{opacity:0}to{opacity:1} }
    @keyframes fadeUp {
      from{opacity:0;transform:translateY(22px)}
      to{opacity:1;transform:translateY(0)}
    }
 
    /* Responsive HP */
    @media (max-width: 768px) {
      .hero-text { display: none; }
      .brand-corner { left:24px; top:24px; }
      .page-content {
        justify-content: center;
        padding: 100px 20px 40px;
        align-items: flex-start;
      }
      .login-wrap { width: 100%; max-width: 400px; }
    }
  </style>
</head>
<body>
 
  <!-- Background foto roti asli -->
  <div class="bg-photo"></div>
  <div class="bg-overlay"></div>
 
  <!-- Brand pojok kiri atas -->
  <div class="brand-corner">
    <div class="brand-name">Azza <span>Bakery</span></div>
    <div class="brand-tagline">Artisan Bakery & Pastry</div>
    <div class="brand-line"></div>
  </div>
 
  <!-- Teks hero kiri bawah -->
  <div class="hero-text">
    <div class="hero-badge">Admin Portal</div>
    <h1>Selamat<br>datang <em>kembali,</em><br>Admin</h1>
    <p>Kelola produk, pantau pesanan masuk, dan moderasi ulasan pelanggan Azza Bakery.</p>
    <div class="dots">
      <span></span><span></span><span></span>
    </div>
  </div>
 
  <!-- Konten utama -->
  <div class="page-content">
    <div class="login-wrap">
      <div class="login-card">
 
        <div class="card-title">Masuk</div>
        <div class="card-sub">Gunakan akun admin Azza Bakery</div>
 
        <!-- Pesan error -->
        <?php if ($error != "") { ?>
          <div class="error-box"><?= $error ?></div>
        <?php } ?>
 
        <form method="POST">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username"
              placeholder="Masukkan username" required autocomplete="off">
          </div>
          <!-- Ganti bagian form-group password yang lama dengan ini -->
<div class="form-group">
  <label for="password">Password</label>
  
  <!-- Wrapper baru untuk input + ikon mata -->
  <div style="position: relative;">
    
    <input 
      type="password" 
      id="password" 
      name="password"
      placeholder="Masukkan password" 
      required
      style="padding-right: 48px;" <!-- beri ruang untuk ikon mata -->
    >

    <!-- Tombol ikon mata -->
    <button 
      type="button" 
      id="togglePassword"
      onclick="lihatPassword()"
      style="
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        color: rgba(255,235,235,0.45);
        font-size: 18px;
        line-height: 1;
        transition: color 0.2s;
      "
      onmouseover="this.style.color='rgba(212,168,71,0.9)'"
      onmouseout="this.style.color='rgba(255,235,235,0.45)'"
    >
      <!-- Ikon mata TERTUTUP (default — password disembunyikan) -->
      <svg id="iconMata" xmlns="http://www.w3.org/2000/svg" 
        width="20" height="20" viewBox="0 0 24 24" 
        fill="none" stroke="currentColor" 
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/>
        <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
        <line x1="1" y1="1" x2="23" y2="23"/>
      </svg>
    </button>

  </div>
</div>

          <button type="submit" name="submit" class="btn-login">
            Masuk ke Dashboard
          </button>
        </form>
 
        <div class="divider">
          <div class="div-line"></div>
          <div class="div-text">AZZA BAKERY</div>
          <div class="div-line"></div>
        </div>
 
        <div class="card-footer">
          &copy; <?= date('Y') ?> Azza Bakery — All rights reserved
        </div>
 
      </div>
    </div>
  </div>
 
  <div class="page-footer">v1.0 &nbsp;·&nbsp; Admin System</div>
 
  <script>
function lihatPassword() {
  // Ambil elemen input password dan ikon mata
  var inputPassword = document.getElementById('password');
  var iconMata      = document.getElementById('iconMata');

  // Cek: kalau sekarang type-nya "password" → ganti jadi "text" (kelihatan)
  // Kalau sudah "text" → ganti balik jadi "password" (tersembunyi)
  if (inputPassword.type === 'password') {
    
    // Tampilkan password
    inputPassword.type = 'text';

    // Ganti ikon jadi mata TERBUKA
    iconMata.innerHTML = `
      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
      <circle cx="12" cy="12" r="3"/>
    `;

  } else {

    // Sembunyikan password
    inputPassword.type = 'password';

    // Ganti balik jadi mata TERTUTUP (dengan garis coret)
    iconMata.innerHTML = `
      <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/>
      <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
      <line x1="1" y1="1" x2="23" y2="23"/>
    `;

  }
}
</script>
</body>
</html>