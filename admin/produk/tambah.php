<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
require_once "../../config/koneksi.php";

$error = "";

if (isset($_POST['submit'])) {
    $nama_produk = trim($_POST['nama_produk']);
    $harga       = trim($_POST['harga']);
    $deskripsi   = trim($_POST['deskripsi']);
    $kategori    = $_POST['kategori'];
    $nama_file   = "";

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $nama_asli = $_FILES['gambar']['name'];
        $ukuran    = $_FILES['gambar']['size'];
        $tmp_path  = $_FILES['gambar']['tmp_name'];
        $ekstensi  = strtolower(pathinfo($nama_asli, PATHINFO_EXTENSION));
        $ekstensi_ok = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ekstensi, $ekstensi_ok)) {
            $error = "Format foto tidak didukung! Gunakan JPG, PNG, atau WEBP.";
        } elseif ($ukuran > 2 * 1024 * 1024) {
            $error = "Ukuran foto terlalu besar! Maksimal 2MB.";
        } else {
            $nama_file = time() . "_" . preg_replace('/\s+/', '_', $nama_asli);
            $tujuan    = "../../uploads/" . $nama_file;
            if (!move_uploaded_file($tmp_path, $tujuan)) {
                $error = "Gagal upload foto. Coba lagi.";
                $nama_file = "";
            }
        }
    }

    if ($error == "") {
        $query = "INSERT INTO produk (nama_produk, harga, deskripsi, gambar, kategori)
                  VALUES ('$nama_produk', '$harga', '$deskripsi', '$nama_file', '$kategori')";
        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php?pesan=tambah");
            exit();
        } else {
            $error = "Gagal menyimpan data. Silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Produk — Azza Bakery Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    /* =============================================
       PALET — Gradient 2 "The Great Pumpkin"
       #F28A2E (amber) → #BF4904 (rust) → #732002 (deep maroon)
       ============================================= */
    :root {
      --amber:        #F28A2E;
      --amber-light:  #F2AF5C;
      --rust:         #BF4904;
      --rust-dark:    #8B3200;
      --maroon-deep:  #732002;
      --maroon-black: #260101;
      --gold:         #E8B84B;
      --gold-pale:    #F5D98A;
      --cream:        #FDF6E3;

      /* Glass */
      --glass-bg:     rgba(242, 138, 46, 0.1);
      --glass-border: rgba(242, 175, 92, 0.3);
      --text-light:   #FFF8F0;
      --text-muted:   rgba(255, 235, 200, 0.65);
      --text-label:   rgba(255, 220, 160, 0.85);

      --sidebar-w:    260px;
    }

    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      min-height: 100vh;
      display: flex;
      /* Background gelap maroon sebagai base */
      background: linear-gradient(145deg, #260101 0%, #732002 40%, #3D1200 100%);
    }

    /* Pola titik halus di background */
    body::before {
      content: '';
      position: fixed; inset: 0;
      background-image: radial-gradient(circle, rgba(242,175,92,0.06) 1px, transparent 1px);
      background-size: 30px 30px;
      pointer-events: none; z-index: 0;
    }

    a { text-decoration: none; color: inherit; }

    /* =============================================
       SIDEBAR — glass dark
       ============================================= */
    .sidebar {
      width: var(--sidebar-w);
      background: linear-gradient(180deg,
        rgba(120, 50, 5, 0.92) 0%,
        rgba(80, 20, 0, 0.95) 40%,
        rgba(38, 1, 1, 0.98) 100%
    );
    border-right: 1px solid rgba(242, 138, 46, 0.3);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-right: 1px solid rgba(242,138,46,0.15);
      min-height: 100vh;
      position: fixed; left:0; top:0;
      display: flex; flex-direction: column;
      z-index: 100;
    }

    .sidebar-logo {
      padding: 28px 24px 24px;
      background: rgba(242, 138, 46, 0.08);
      border-bottom: 1px solid rgba(242,138,46,0.15);
      display: flex; align-items: center; gap: 14px;
    }
    .sidebar-logo img {
      width: 52px; height: 52px; border-radius: 10px;
      object-fit: cover; border: 2px solid rgba(242,175,92,0.4);
    }
    .sidebar-logo-text .brand {
      font-family: 'Cormorant Garamond', serif;
      font-size: 20px; font-weight: 700;
      color: var(--text-light); line-height: 1.1;
    }
    .sidebar-logo-text .brand span { color: var(--gold); }
    .sidebar-logo-text .sub {
      font-size: 10px; font-weight: 300; color: var(--text-muted);
      letter-spacing: 2px; text-transform: uppercase; margin-top: 2px;
    }

    .sidebar-admin { padding: 20px 24px; border-bottom: 1px solid rgba(242,138,46,0.1); }
    .admin-badge { display: flex; align-items: center; gap: 12px; }
    .admin-avatar {
      width: 38px; height: 38px; border-radius: 50%;
      background: linear-gradient(135deg, var(--amber), var(--rust-dark));
      display: flex; align-items: center; justify-content: center;
      font-family: 'Cormorant Garamond', serif;
      font-size: 16px; font-weight: 700; color: white; flex-shrink: 0;
    }
    .admin-info .name { font-size: 13.5px; font-weight: 500; color: var(--text-light); }
    .admin-info .role { font-size: 11px; color: var(--text-muted); margin-top: 1px; }

    .sidebar-nav { padding: 20px 16px; flex: 1; }
    .nav-label {
      font-size: 10px; font-weight: 600; color: rgba(242,175,92,0.35);
      letter-spacing: 2.5px; text-transform: uppercase; padding: 0 8px; margin-bottom: 8px;
    }
    .nav-item {
      display: flex; align-items: center; gap: 12px;
      padding: 11px 12px; border-radius: 10px; margin-bottom: 4px;
      font-size: 14px; color: var(--text-muted);
      transition: background .2s, color .2s;
    }
    .nav-item:hover { background: rgba(242,138,46,0.12); color: var(--gold); }
    .nav-item.active {
      background: rgba(242,138,46,0.18); color: var(--gold); font-weight: 500;
      border: 1px solid rgba(242,138,46,0.25);
    }
    .nav-icon { width: 18px; height: 18px; opacity: 0.7; flex-shrink: 0; }
    .nav-item.active .nav-icon, .nav-item:hover .nav-icon { opacity: 1; }

    .sidebar-logout { padding: 16px; border-top: 1px solid rgba(242,138,46,0.12); }
    .btn-logout {
      display: flex; align-items: center; justify-content: center; gap: 8px;
      width: 100%; padding: 11px;
      background: rgba(191,73,4,0.2); border: 1px solid rgba(242,138,46,0.2);
      border-radius: 10px; color: var(--amber);
      font-size: 13.5px; font-weight: 500; cursor: pointer;
      transition: background .2s;
    }
    .btn-logout:hover { background: rgba(191,73,4,0.35); }

    /* =============================================
       KONTEN UTAMA
       ============================================= */
    .main-content {
      margin-left: var(--sidebar-w);
      flex: 1; padding: 36px 44px;
      min-height: 100vh;
      position: relative; z-index: 1;
      display: flex; flex-direction: column;
    }

    /* Breadcrumb */
    .breadcrumb {
      display: flex; align-items: center; gap: 8px;
      font-size: 13px; color: var(--text-muted); margin-bottom: 28px;
    }
    .breadcrumb a { color: rgba(242,175,92,0.7); transition: color .2s; }
    .breadcrumb a:hover { color: var(--gold); }
    .breadcrumb-sep { opacity: 0.35; }
    .breadcrumb-current { color: var(--gold-pale); font-weight: 500; }

    /* =============================================
       SPLIT PANEL CARD
       ============================================= */
    .split-card {
      display: flex;
      border-radius: 28px;
      overflow: hidden;
      /* Glass effect di container luar */
      background: rgba(255,255,255,0.20);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255,255,255,0.25);
      box-shadow:
        0 24px 64px rgba(38,1,1,0.4),
        inset 0 1px 0 rgba(255,255,255,0.2);
      animation: fadeUp .7s cubic-bezier(.16,1,.3,1) both;
      min-height: 500px;
    }

    @keyframes fadeUp {
      from { opacity:0; transform:translateY(28px); }
      to   { opacity:1; transform:translateY(0); }
    }

    /* =============================================
       PANEL KIRI — ilustrasi animasi bakery
       ============================================= */
    .panel-left {
      width: 30%;
      flex-shrink: 0;
      /* Gradient 2 dari palet Pinterest */
      background: linear-gradient(160deg,
        #F2AF5C 0%,
        #F28A2E 25%,
        #BF4904 60%,
        #732002 85%,
        #260101 100%
      );
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 32px 28px;
    }

    /* Lingkaran dekoratif animasi di background panel kiri */
    .deco-circle {
      position: absolute;
      border-radius: 50%;
      background: rgba(255,255,255,0.06);
      animation: floatUp 6s ease-in-out infinite;
    }

    .deco-circle:nth-child(1) {
      width: 280px; height: 280px;
      top: -80px; right: -80px;
      animation-delay: 0s;
    }
    .deco-circle:nth-child(2) {
      width: 180px; height: 180px;
      bottom: -60px; left: -50px;
      animation-delay: 2s;
    }
    .deco-circle:nth-child(3) {
      width: 100px; height: 100px;
      top: 40%; left: 10%;
      animation-delay: 4s;
      background: rgba(255,255,255,0.04);
    }

    @keyframes floatUp {
      0%, 100% { transform: translateY(0) scale(1); }
      50%       { transform: translateY(-16px) scale(1.04); }
    }

    /* Ilustrasi SVG roti — animasi mengambang */
    .bakery-illustration {
      position: relative; z-index: 2;
      margin-bottom: 28px;
      animation: float 4s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50%       { transform: translateY(-12px); }
    }

    /* Bayangan mengambang di bawah ilustrasi */
    .float-shadow {
      width: 100px; height: 16px;
      background: rgba(38,1,1,0.4);
      border-radius: 50%;
      margin: 0 auto;
      animation: shadowPulse 4s ease-in-out infinite;
      filter: blur(6px);
    }

    @keyframes shadowPulse {
      0%, 100% { transform: scaleX(1); opacity: .5; }
      50%       { transform: scaleX(.75); opacity: .25; }
    }

    /* Teks di panel kiri */
    .panel-left-text {
      position: relative; z-index: 2;
      text-align: center; margin-top: 24px;
    }

    .panel-left-text .tagline {
      font-size: 10px; font-weight: 600;
      color: rgba(255,248,230,0.55);
      letter-spacing: 3px; text-transform: uppercase;
      margin-bottom: 10px;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .panel-left-text .tagline::before,
    .panel-left-text .tagline::after {
      content: ''; width: 20px; height: 1px;
      background: rgba(255,248,230,0.3);
    }

    .panel-left-text h2 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 30px; font-weight: 700; font-style: italic;
      color: var(--cream); line-height: 1.2; margin-bottom: 10px;
    }

    .panel-left-text p {
      font-size: 13px; color: rgba(255,248,230,0.6);
      line-height: 1.7;
    }

    /* Titik-titik dekoratif bawah */
    .panel-dots {
      position: relative; z-index: 2;
      display: flex; gap: 6px; margin-top: 28px;
    }
    .panel-dots span {
      width: 7px; height: 7px; border-radius: 50%;
      background: rgba(255,248,230,0.35);
    }
    .panel-dots span:nth-child(2) {
      background: rgba(255,248,230,0.7);
      width: 20px; border-radius: 4px;
    }

    /* =============================================
       PANEL KANAN — form glassmorphism
       ============================================= */
    .panel-right {
      flex: 1;
      padding: 28px 32px 28px;
      /* Glass tipis untuk panel kanan */
      background: rgba(255,255,255,0.04);
      border-left: 1px solid rgba(255,255,255,0.1);
      overflow-y: auto;
    }

    .form-header { margin-bottom: 28px; }

    .eyebrow {
      font-size: 10px; font-weight: 600;
      color: var(--amber); letter-spacing: 3px;
      text-transform: uppercase; margin-bottom: 8px;
      display: flex; align-items: center; gap: 8px;
    }
    .eyebrow::before {
      content: ''; width: 20px; height: 1.5px;
      background: var(--amber);
    }

    .form-header h1 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 30px; font-weight: 700;
      color: var(--text-light); line-height: 1.1; margin-bottom: 6px;
    }

    .form-header p { font-size: 13px; color: var(--text-muted); line-height: 1.6; }

    /* Divider tipis */
    .form-divider {
      width: 100%; height: 1px;
      background: rgba(242,138,46,0.15);
      margin: 20px 0 24px;
    }

    /* Error box */
    .error-box {
      background: rgba(191,73,4,0.2);
      border: 1px solid rgba(242,138,46,0.3);
      border-left: 3px solid var(--amber);
      border-radius: 10px;
      padding: 12px 16px; margin-bottom: 22px;
      font-size: 13px; color: var(--amber-light);
      animation: shake .35s ease;
    }
    @keyframes shake {
      0%,100%{transform:translateX(0)}
      25%{transform:translateX(-6px)}
      75%{transform:translateX(6px)}
    }

    /* Grid 2 kolom */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .form-group { margin-bottom: 2px; }
    .form-group.full { grid-column: 1 / -1; }

    /* Label */
    .form-group label {
      display: block; font-size: 10px; font-weight: 600;
      color: var(--text-label); letter-spacing: 2.5px;
      text-transform: uppercase; margin-bottom: 8px;
    }
    .required { color: var(--amber); }

    /* Input, select, textarea */
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group textarea,
    .form-group select {
      width: 100%; padding: 9px 13px;
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.2);
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 16px; color: var(--text-light);
      outline: none;
      transition: border-color .2s, background .2s, box-shadow .2s;
    }
    .form-group input::placeholder,
    .form-group textarea::placeholder { color: rgba(242,175,92,0.28); }
    .form-group select option { background: #3D1200; color: white; }
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      border-color: rgba(242,138,46,0.55);
      background: rgba(242,138,46,0.13);
      box-shadow: 0 0 0 4px rgba(242,138,46,0.1);
    }
    .form-group textarea { resize: vertical; min-height: 88px; line-height: 1.6; }

    /* Upload area */
    .upload-area {
      border: 1.5px dashed rgba(242,138,46,0.35);
      border-radius: 12px;
      background: rgba(242,138,46,0.06);
      padding: 18px 16px; text-align: center;
      cursor: pointer; position: relative;
      transition: border-color .2s, background .2s;
    }
    .upload-area:hover {
      border-color: rgba(242,138,46,0.6);
      background: rgba(242,138,46,0.1);
    }
    .upload-area input[type="file"] {
      position: absolute; inset: 0;
      opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .upload-icon { font-size: 30px; margin-bottom: 8px; opacity: 0.55; }
    .upload-text { font-size: 13px; color: var(--text-muted); line-height: 1.6; }
    .upload-text strong { color: var(--amber); }
    .upload-note { font-size: 11.5px; color: rgba(242,175,92,0.38); margin-top: 5px; }

    /* Preview foto */
    #preview-wrap { display:none; margin-top: 14px; text-align: center; }
    #preview-img {
      max-width: 120px; max-height: 120px;
      border-radius: 10px; object-fit: cover;
      border: 2px solid rgba(242,138,46,0.35);
      box-shadow: 0 8px 24px rgba(38,1,1,0.4);
    }
    #preview-nama { font-size: 11.5px; color: var(--text-muted); margin-top: 6px; }

    /* Tombol aksi */
    .form-footer {
      display: flex; align-items: center; gap: 12px;
      margin-top: 20px; padding-top: 22px;
      border-top: 1px solid rgba(242,138,46,0.12);
    }

    .btn-simpan {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 26px;
      background: linear-gradient(135deg, var(--rust) 0%, var(--maroon-deep) 100%);
      color: var(--cream);
      font-family: 'Cormorant Garamond', serif;
      font-size: 17px; font-weight: 600; letter-spacing: 0.5px;
      border: 1px solid rgba(242,138,46,0.25);
      border-radius: 10px; cursor: pointer;
      box-shadow: 0 4px 20px rgba(38,1,1,0.45);
      transition: transform .15s, box-shadow .15s, filter .15s;
      position: relative; overflow: hidden;
    }
    .btn-simpan::after {
      content: '';
      position: absolute; top:0; left:-100%;
      width:60%; height:100%;
      background: linear-gradient(to right, transparent, rgba(255,255,255,.1), transparent);
      transform: skewX(-20deg); transition: left .5s ease;
    }
    .btn-simpan:hover::after { left: 160%; }
    .btn-simpan:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 28px rgba(38,1,1,0.55);
      filter: brightness(1.1);
    }
    .btn-simpan:active { transform: translateY(0); }

    .btn-batal {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 12px 18px;
      background: transparent; color: var(--text-muted);
      font-size: 13.5px;
      border: 1px solid rgba(242,138,46,0.18);
      border-radius: 10px; cursor: pointer;
      transition: border-color .2s, color .2s;
    }
    .btn-batal:hover { border-color: rgba(242,138,46,0.4); color: var(--amber); }
  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-logo">
      <img src="../../uploads/logo_azza.jpg" alt="Logo Azza Bakery">
      <div class="sidebar-logo-text">
        <div class="brand">Azza <span>Bakery</span></div>
        <div class="sub">Admin Panel</div>
      </div>
    </div>
    <div class="sidebar-admin">
      <div class="admin-badge">
        <div class="admin-avatar"><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></div>
        <div class="admin-info">
          <div class="name"><?= $_SESSION['username'] ?></div>
          <div class="role">Administrator</div>
        </div>
      </div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-label">Menu Utama</div>
      <a href="../dashboard.php" class="nav-item">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/>
          <rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        Dashboard
      </a>
      <a href="index.php" class="nav-item active">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        Kelola Produk
      </a>
      <!-- Kelola Artikel — tambahkan ini -->
<a href="../artikel/index.php" class="nav-item">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="16" y1="13" x2="8" y2="13"/>
        <line x1="16" y1="17" x2="8" y2="17"/>
        <polyline points="10 9 9 9 8 9"/>
    </svg>
    Kelola Artikel
</a>
    </nav>
    <div class="sidebar-logout">
      <a href="../logout.php" class="btn-logout">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Logout
      </a>
    </div>
  </aside>

  <!-- KONTEN UTAMA -->
  <main class="main-content">

    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <a href="../dashboard.php">Dashboard</a>
      <span class="breadcrumb-sep">›</span>
      <a href="index.php">Kelola Produk</a>
      <span class="breadcrumb-sep">›</span>
      <span class="breadcrumb-current">Tambah Produk</span>
    </div>

    <!-- SPLIT PANEL CARD -->
    <div class="split-card">

      <!-- ===== PANEL KIRI — Ilustrasi Bakery ===== -->
      <div class="panel-left">

        <!-- Lingkaran dekoratif mengambang -->
        <div class="deco-circle"></div>
        <div class="deco-circle"></div>
        <div class="deco-circle"></div>

        <!-- Ilustrasi roti SVG — pure native, animasi mengambang -->
        <div class="bakery-illustration">
  <svg width="190" height="190" viewBox="0 0 190 190" xmlns="http://www.w3.org/2000/svg">

    <!-- Bayangan bawah pie -->
    <ellipse cx="95" cy="162" rx="58" ry="9" fill="rgba(38,1,1,0.35)"/>

    <!-- Badan pie bawah — sisi samping -->
    <ellipse cx="95" cy="138" rx="62" ry="16" fill="#5C1E00"/>
    <rect x="33" y="122" width="124" height="20" fill="#5C1E00"/>
    <ellipse cx="95" cy="122" rx="62" ry="16" fill="#8B3200"/>

    <!-- Pinggiran pie / crust bawah -->
    <path d="M 33 122 Q 33 108 95 108 Q 157 108 157 122 L 157 138 Q 157 152 95 152 Q 33 152 33 138 Z"
      fill="url(#crustGrad)"/>

    <!-- Isi pie — filling tampak dari sisi -->
    <path d="M 38 122 Q 38 115 95 115 Q 152 115 152 122"
      fill="#C4501A" opacity="0.8"/>

    <!-- Tutup pie atas — bulat -->
    <ellipse cx="95" cy="108" rx="62" ry="20" fill="url(#topCrustGrad)"/>

    <!-- Tekstur tutup pie — garis lengkung -->
    <path d="M 50 100 Q 95 92 140 100" stroke="rgba(255,220,140,0.3)" stroke-width="1.5" fill="none"/>
    <path d="M 42 106 Q 95 97 148 106" stroke="rgba(255,220,140,0.2)" stroke-width="1" fill="none"/>

    <!-- Lubang uap di atas pie (3 lubang kecil) -->
    <ellipse cx="78"  cy="105" rx="5" ry="3.5" fill="#3D1200" transform="rotate(-10 78 105)"/>
    <ellipse cx="95"  cy="102" rx="5" ry="3.5" fill="#3D1200"/>
    <ellipse cx="112" cy="105" rx="5" ry="3.5" fill="#3D1200" transform="rotate(10 112 105)"/>

    <!-- Kilap tutup pie -->
    <ellipse cx="78" cy="103" rx="18" ry="7"
      fill="rgba(255,255,255,0.13)" transform="rotate(-8 78 103)"/>

    <!-- Pinggiran crust atas bergelombang -->
    <path d="
      M 33 122
      Q 38 108 50 105
      Q 58 101 65 106
      Q 72 111 80 105
      Q 88 99  95 102
      Q 102 99 110 105
      Q 118 111 125 106
      Q 132 101 140 105
      Q 152 108 157 122
    " fill="url(#edgeCrustGrad)" opacity="0.95"/>

    <!-- Isian pie yang keluar sedikit dari celah crust -->
    <path d="M 65 106 Q 72 114 80 105" fill="#C4501A" opacity="0.6"/>
    <path d="M 110 105 Q 118 114 125 106" fill="#C4501A" opacity="0.6"/>

    <!-- Uap panas dari lubang — animasi -->
    <g opacity="0.6">
      <!-- Uap lubang kiri -->
      <path d="M 78 100 Q 75 92 78 84 Q 81 76 78 68"
        stroke="rgba(255,248,230,0.65)" stroke-width="2" fill="none" stroke-linecap="round">
        <animate attributeName="opacity" values="0.6;0.1;0.6" dur="2.5s" repeatCount="indefinite"/>
        <animate attributeName="d"
          values="M 78 100 Q 75 92 78 84 Q 81 76 78 68;M 78 100 Q 81 92 78 84 Q 75 76 78 68;M 78 100 Q 75 92 78 84 Q 81 76 78 68"
          dur="2.5s" repeatCount="indefinite"/>
      </path>
      <!-- Uap lubang tengah -->
      <path d="M 95 97 Q 92 88 95 79 Q 98 70 95 61"
        stroke="rgba(255,248,230,0.65)" stroke-width="2.5" fill="none" stroke-linecap="round">
        <animate attributeName="opacity" values="0.6;0.1;0.6" dur="3s" begin="0.5s" repeatCount="indefinite"/>
        <animate attributeName="d"
          values="M 95 97 Q 92 88 95 79 Q 98 70 95 61;M 95 97 Q 98 88 95 79 Q 92 70 95 61;M 95 97 Q 92 88 95 79 Q 98 70 95 61"
          dur="3s" begin="0.5s" repeatCount="indefinite"/>
      </path>
      <!-- Uap lubang kanan -->
      <path d="M 112 100 Q 109 91 112 83 Q 115 74 112 66"
        stroke="rgba(255,248,230,0.65)" stroke-width="2" fill="none" stroke-linecap="round">
        <animate attributeName="opacity" values="0.6;0.1;0.6" dur="2s" begin="1s" repeatCount="indefinite"/>
        <animate attributeName="d"
          values="M 112 100 Q 109 91 112 83 Q 115 74 112 66;M 112 100 Q 115 91 112 83 Q 109 74 112 66;M 112 100 Q 109 91 112 83 Q 115 74 112 66"
          dur="2s" begin="1s" repeatCount="indefinite"/>
      </path>
    </g>

    <!-- Bintang kecil berkedip dekoratif -->
    <circle cx="152" cy="82" r="2.5" fill="rgba(255,220,140,0.7)">
      <animate attributeName="opacity" values="0.7;0.1;0.7" dur="2s" repeatCount="indefinite"/>
    </circle>
    <circle cx="35" cy="88" r="2" fill="rgba(255,220,140,0.55)">
      <animate attributeName="opacity" values="0.55;0.1;0.55" dur="3s" begin="1s" repeatCount="indefinite"/>
    </circle>
    <circle cx="160" cy="110" r="1.5" fill="rgba(255,220,140,0.4)">
      <animate attributeName="opacity" values="0.4;0.1;0.4" dur="2.5s" begin="0.5s" repeatCount="indefinite"/>
    </circle>
    <circle cx="30" cy="115" r="1.8" fill="rgba(255,220,140,0.45)">
      <animate attributeName="opacity" values="0.45;0.1;0.45" dur="1.8s" begin="1.2s" repeatCount="indefinite"/>
    </circle>

    <!-- Gradient definitions -->
    <defs>
      <linearGradient id="crustGrad" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0%"   stop-color="#D4732A"/>
        <stop offset="100%" stop-color="#8B3200"/>
      </linearGradient>
      <linearGradient id="topCrustGrad" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0%"   stop-color="#F2AF5C"/>
        <stop offset="40%"  stop-color="#D4732A"/>
        <stop offset="100%" stop-color="#BF4904"/>
      </linearGradient>
      <linearGradient id="edgeCrustGrad" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0%"   stop-color="#F2C080"/>
        <stop offset="100%" stop-color="#C46020"/>
      </linearGradient>
    </defs>

  </svg>
</div>

        <!-- Bayangan mengambang -->
        <div class="float-shadow"></div>

        <!-- Teks panel kiri -->
        <div class="panel-left-text">
          <div class="tagline">Azza Bakery</div>
          <h2>Produk Baru<br>yang Menggoda</h2>
          <p>Tambahkan menu terbaru dan buat pelanggan semakin penasaran.</p>
        </div>

        <!-- Titik-titik dekoratif -->
        <div class="panel-dots">
          <span></span><span></span><span></span>
        </div>

      </div>
      <!-- end panel kiri -->

      <!-- ===== PANEL KANAN — Form ===== -->
      <div class="panel-right">

        <div class="form-header">
          <div class="eyebrow">Formulir Baru</div>
          <h1>Tambah Produk</h1>
          <p>Isi detail produk untuk ditampilkan di katalog Azza Bakery.</p>
        </div>

        <div class="form-divider"></div>

        <?php if ($error != "") { ?>
          <div class="error-box"><?= $error ?></div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">
          <div class="form-grid">

            <div class="form-group">
              <label>Nama Produk <span class="required">*</span></label>
              <input type="text" name="nama_produk"
                placeholder="contoh: Roti Coklat Lembut"
                value="<?= isset($_POST['nama_produk']) ? $_POST['nama_produk'] : '' ?>" required>
            </div>

            <div class="form-group">
              <label>Harga (Rp) <span class="required">*</span></label>
              <input type="number" name="harga"
                placeholder="contoh: 15000"
                value="<?= isset($_POST['harga']) ? $_POST['harga'] : '' ?>"
                min="0" required>
            </div>

            <div class="form-group">
              <label>Kategori <span class="required">*</span></label>
              <select name="kategori" required>
                <option value="">— Pilih Kategori —</option>
                <option value="Roti Manis"  <?= (isset($_POST['kategori']) && $_POST['kategori']=='Roti Manis')  ? 'selected':'' ?>>Roti Manis</option>
                <option value="Kue Kering"  <?= (isset($_POST['kategori']) && $_POST['kategori']=='Kue Kering')  ? 'selected':'' ?>>Kue Kering</option>
                <option value="Pastry"      <?= (isset($_POST['kategori']) && $_POST['kategori']=='Pastry')      ? 'selected':'' ?>>Pastry</option>
                <option value="Lainnya"     <?= (isset($_POST['kategori']) && $_POST['kategori']=='Lainnya')     ? 'selected':'' ?>>Lainnya</option>
              </select>
            </div>

            <div class="form-group">
              <label>Deskripsi Singkat</label>
              <textarea name="deskripsi"
                placeholder="Ceritakan keunikan produk ini..."><?= isset($_POST['deskripsi']) ? $_POST['deskripsi'] : '' ?></textarea>
            </div>

            <div class="form-group full">
              <label>Foto Produk</label>
              <div class="upload-area">
                <input type="file" name="gambar" id="inputFoto"
                  accept=".jpg,.jpeg,.png,.webp"
                  onchange="previewFoto(this)">
                <div class="upload-icon">📷</div>
                <div class="upload-text">
                  <strong>Klik untuk pilih foto</strong> atau seret ke sini
                </div>
                <div class="upload-note">Format: JPG, PNG, WEBP · Maksimal 2MB</div>
              </div>
              <div id="preview-wrap">
                <img id="preview-img" src="" alt="Preview">
                <div id="preview-nama"></div>
              </div>
            </div>

          </div>

          <div class="form-footer">
            <button type="submit" name="submit" class="btn-simpan">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
                <polyline points="7 3 7 8 15 8"/>
              </svg>
              Simpan Produk
            </button>
            <a href="index.php" class="btn-batal">← Batal</a>
          </div>

        </form>

      </div>
      <!-- end panel kanan -->

    </div>
    <!-- end split-card -->

  </main>

  <script>
    function previewFoto(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('preview-img').src = e.target.result;
          document.getElementById('preview-nama').textContent = input.files[0].name;
          document.getElementById('preview-wrap').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>

</body>
</html>