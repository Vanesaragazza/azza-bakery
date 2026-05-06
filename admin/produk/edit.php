<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
require_once "../../config/koneksi.php";

// Ambil id dari URL, kalau tidak ada tendang ke index
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = $_GET['id'];

// Ambil data produk lama dari database
$query_produk = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id'");
if (mysqli_num_rows($query_produk) == 0) {
    header("Location: index.php");
    exit();
}
$produk = mysqli_fetch_assoc($query_produk);
$error  = "";

// Proses update saat form di-submit
if (isset($_POST['submit'])) {
    $nama_produk = trim($_POST['nama_produk']);
    $harga       = trim($_POST['harga']);
    $deskripsi   = trim($_POST['deskripsi']);
    $kategori    = $_POST['kategori'];
    $nama_file   = $produk['gambar']; // default pakai foto lama

    // Proses upload foto baru jika ada
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $nama_asli = $_FILES['gambar']['name'];
        $ukuran    = $_FILES['gambar']['size'];
        $tmp_path  = $_FILES['gambar']['tmp_name'];
        $ekstensi  = strtolower(pathinfo($nama_asli, PATHINFO_EXTENSION));
        $ekstensi_ok = ['jpg','jpeg','png','webp'];

        if (!in_array($ekstensi, $ekstensi_ok)) {
            $error = "Format foto tidak didukung! Gunakan JPG, PNG, atau WEBP.";
        } elseif ($ukuran > 2 * 1024 * 1024) {
            $error = "Ukuran foto terlalu besar! Maksimal 2MB.";
        } else {
            // Hapus foto lama
            $foto_lama = "../../uploads/" . $produk['gambar'];
            if (!empty($produk['gambar']) && file_exists($foto_lama)) {
                unlink($foto_lama);
            }
            $nama_file = time() . "_" . preg_replace('/\s+/', '_', $nama_asli);
            if (!move_uploaded_file($tmp_path, "../../uploads/" . $nama_file)) {
                $error = "Gagal upload foto. Coba lagi.";
                $nama_file = $produk['gambar'];
            }
        }
    }

    if ($error == "") {
        $q = "UPDATE produk SET
                nama_produk='$nama_produk', harga='$harga',
                deskripsi='$deskripsi', gambar='$nama_file', kategori='$kategori'
              WHERE id_produk='$id'";
        if (mysqli_query($koneksi, $q)) {
            header("Location: index.php?pesan=edit");
            exit();
        } else {
            $error = "Gagal memperbarui data. Silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Produk — Azza Bakery Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --amber:       #F28A2E;
      --amber-light: #F2AF5C;
      --rust:        #BF4904;
      --rust-dark:   #8B3200;
      --maroon-deep: #732002;
      --maroon-dark: #260101;
      --gold:        #E8B84B;
      --gold-pale:   #F5D98A;
      --cream:       #FDF6E3;
      --text-light:  #FFF8F0;
      --text-muted:  rgba(255,235,200,0.65);
      --text-label:  rgba(255,220,160,0.85);
      --sidebar-w:   260px;
    }
    *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
    body{
      font-family:'DM Sans',sans-serif;
      min-height:100vh; display:flex;
      background:linear-gradient(145deg,#260101 0%,#732002 40%,#3D1200 100%);
    }
    body::before{
      content:''; position:fixed; inset:0;
      background-image:radial-gradient(circle,rgba(242,175,92,.06) 1px,transparent 1px);
      background-size:30px 30px; pointer-events:none; z-index:0;
    }
    a{text-decoration:none;color:inherit}

    /* SIDEBAR */
    .sidebar{
      width:var(--sidebar-w);
      background: linear-gradient(180deg,
        rgba(120, 50, 5, 0.92) 0%,
        rgba(80, 20, 0, 0.95) 40%,
        rgba(38, 1, 1, 0.98) 100%
    );
      backdrop-filter:blur(20px);
      -webkit-backdrop-filter:blur(20px);
      border-right: 1px solid rgba(242, 138, 46, 0.3);
      min-height:100vh; position:fixed; left:0; top:0;
      display:flex; flex-direction:column; z-index:100;
    }
    .sidebar-logo{padding:28px 24px 24px;border-bottom:1px solid rgba(242,138,46,0.08);display:flex;align-items:center;gap:14px}
    .sidebar-logo img{width:52px;height:52px;border-radius:10px;object-fit:cover;border:2px solid rgba(242,175,92,.4)}
    .sidebar-logo-text .brand{font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--text-light);line-height:1.1}
    .sidebar-logo-text .brand span{color:var(--gold)}
    .sidebar-logo-text .sub{font-size:10px;font-weight:300;color:var(--text-muted);letter-spacing:2px;text-transform:uppercase;margin-top:2px}
    .sidebar-admin{padding:20px 24px;border-bottom:1px solid rgba(242,138,46,.1)}
    .admin-badge{display:flex;align-items:center;gap:12px}
    .admin-avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--amber),var(--rust-dark));display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:700;color:white;flex-shrink:0}
    .admin-info .name{font-size:13.5px;font-weight:500;color:var(--text-light)}
    .admin-info .role{font-size:11px;color:var(--text-muted);margin-top:1px}
    .sidebar-nav{padding:20px 16px;flex:1}
    .nav-label{font-size:10px;font-weight:600;color:rgba(242,175,92,.35);letter-spacing:2.5px;text-transform:uppercase;padding:0 8px;margin-bottom:8px}
    .nav-item{display:flex;align-items:center;gap:12px;padding:11px 12px;border-radius:10px;margin-bottom:4px;font-size:14px;color:var(--text-muted);transition:background .2s,color .2s}
    .nav-item:hover{background:rgba(242,138,46,.12);color:var(--gold)}
    .nav-item.active{background:rgba(242,138,46,.18);color:var(--gold);font-weight:500;border:1px solid rgba(242,138,46,.25)}
    .nav-icon{width:18px;height:18px;opacity:.7;flex-shrink:0}
    .nav-item.active .nav-icon,.nav-item:hover .nav-icon{opacity:1}
    .sidebar-logout{padding:16px;border-top:1px solid rgba(242,138,46,.12)}
    .btn-logout{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:11px;background:rgba(191,73,4,.2);border:1px solid rgba(242,138,46,.2);border-radius:10px;color:var(--amber);font-size:13.5px;font-weight:500;cursor:pointer;transition:background .2s}
    .btn-logout:hover{background:rgba(191,73,4,.35)}

    /* MAIN */
    .main-content{margin-left:var(--sidebar-w);flex:1;padding:36px 44px;min-height:100vh;position:relative;z-index:1;display:flex;flex-direction:column}
    .breadcrumb{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-muted);margin-bottom:28px}
    .breadcrumb a{color:rgba(242,175,92,.7);transition:color .2s}
    .breadcrumb a:hover{color:var(--gold)}
    .breadcrumb-sep{opacity:.35}
    .breadcrumb-current{color:var(--gold-pale);font-weight:500}

    /* SPLIT CARD */
    .split-card{
      display:flex; border-radius:28px; overflow:hidden;
      background:rgba(255,255,255,.18);
      backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
      border:1px solid rgba(255,255,255,.25);
      box-shadow:0 24px 64px rgba(38,1,1,.4),inset 0 1px 0 rgba(255,255,255,.2);
      animation:fadeUp .7s cubic-bezier(.16,1,.3,1) both;
      min-height:540px;
    }
    @keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}

    /* PANEL KIRI */
    .panel-left{
      width:40%; flex-shrink:0;
      background:linear-gradient(160deg,#F28A2E 0%,#BF4904 35%,#732002 70%,#3D0800 100%);
      position:relative; overflow:hidden;
      display:flex; flex-direction:column; align-items:center; justify-content:center;
      padding:40px 32px;
    }
    .deco-circle{position:absolute;border-radius:50%;background:rgba(255,255,255,.06);animation:floatUp 6s ease-in-out infinite}
    .deco-circle:nth-child(1){width:260px;height:260px;top:-70px;right:-70px;animation-delay:0s}
    .deco-circle:nth-child(2){width:160px;height:160px;bottom:-50px;left:-40px;animation-delay:2s}
    .deco-circle:nth-child(3){width:90px;height:90px;top:38%;left:8%;animation-delay:4s;background:rgba(255,255,255,.04)}
    @keyframes floatUp{0%,100%{transform:translateY(0) scale(1)}50%{transform:translateY(-14px) scale(1.03)}}
    .bakery-illustration{position:relative;z-index:2;margin-bottom:24px;animation:float 4s ease-in-out infinite}
    @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
    .float-shadow{width:90px;height:14px;background:rgba(38,1,1,.4);border-radius:50%;margin:0 auto;animation:shadowPulse 4s ease-in-out infinite;filter:blur(6px)}
    @keyframes shadowPulse{0%,100%{transform:scaleX(1);opacity:.5}50%{transform:scaleX(.75);opacity:.25}}
    .panel-left-text{position:relative;z-index:2;text-align:center;margin-top:22px}
    .tagline{font-size:10px;font-weight:600;color:rgba(255,248,230,.55);letter-spacing:3px;text-transform:uppercase;margin-bottom:10px;display:flex;align-items:center;justify-content:center;gap:8px}
    .tagline::before,.tagline::after{content:'';width:20px;height:1px;background:rgba(255,248,230,.3)}
    .panel-left-text h2{font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;font-style:italic;color:var(--cream);line-height:1.2;margin-bottom:10px}
    .panel-left-text p{font-size:13px;color:rgba(255,248,230,.6);line-height:1.7}
    .panel-dots{position:relative;z-index:2;display:flex;gap:6px;margin-top:24px}
    .panel-dots span{width:7px;height:7px;border-radius:50%;background:rgba(255,248,230,.35)}
    .panel-dots span:nth-child(2){background:rgba(255,248,230,.7);width:20px;border-radius:4px}

    /* PANEL KANAN */
    .panel-right{flex:1;padding:32px 38px 30px;background:rgba(255,255,255,.06);border-left:1px solid rgba(255,255,255,.15);overflow-y:auto}
    .form-header{margin-bottom:20px}
    .eyebrow{font-size:10px;font-weight:600;color:var(--amber);letter-spacing:3px;text-transform:uppercase;margin-bottom:8px;display:flex;align-items:center;gap:8px}
    .eyebrow::before{content:'';width:20px;height:1.5px;background:var(--amber)}
    .form-header h1{font-family:'Cormorant Garamond',serif;font-size:30px;font-weight:700;color:var(--text-light);line-height:1.1;margin-bottom:5px}
    .form-header p{font-size:13px;color:var(--text-muted);line-height:1.6}
    .form-divider{width:100%;height:1px;background:rgba(242,138,46,.15);margin:16px 0 20px}

    /* Foto saat ini */
    .foto-saat-ini{display:flex;align-items:center;gap:14px;background:rgba(242,138,46,.08);border:1px solid rgba(242,138,46,.2);border-radius:10px;padding:12px 16px;margin-bottom:18px}
    .foto-saat-ini img{width:52px;height:52px;border-radius:8px;object-fit:cover;border:2px solid rgba(242,138,46,.3)}
    .foto-placeholder-kecil{width:52px;height:52px;border-radius:8px;background:rgba(242,138,46,.1);display:flex;align-items:center;justify-content:center;font-size:22px;border:2px solid rgba(242,138,46,.2)}
    .foto-info-label{font-size:10px;font-weight:600;color:var(--amber);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:3px}
    .foto-info-name{font-size:12px;color:var(--text-muted)}

    /* Error */
    .error-box{background:rgba(191,73,4,.2);border:1px solid rgba(242,138,46,.3);border-left:3px solid var(--amber);border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:var(--amber-light);animation:shake .35s ease}
    @keyframes shake{0%,100%{transform:translateX(0)}25%{transform:translateX(-6px)}75%{transform:translateX(6px)}}

    /* Form */
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .form-group{margin-bottom:2px}
    .form-group.full{grid-column:1/-1}
    .form-group label{display:block;font-size:10px;font-weight:600;color:var(--text-label);letter-spacing:2.5px;text-transform:uppercase;margin-bottom:7px}
    .required{color:var(--amber)}
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group textarea,
    .form-group select{
      width:100%;padding:11px 14px;
      background:rgba(255,255,255,.08);
      border:1px solid rgba(255,255,255,.2);
      border-radius:10px;
      font-family:'DM Sans',sans-serif;
      font-size:14px;color:#FFF8F0;outline:none;
      transition:border-color .2s,background .2s,box-shadow .2s;
    }
    .form-group input::placeholder,.form-group textarea::placeholder{color:rgba(242,175,92,.28)}
    .form-group select option{background:#3D1200;color:white}
    .form-group input:focus,.form-group textarea:focus,.form-group select:focus{border-color:rgba(242,138,46,.55);background:rgba(242,138,46,.13);box-shadow:0 0 0 4px rgba(242,138,46,.1)}
    .form-group textarea{resize:vertical;min-height:82px;line-height:1.6}

    /* Upload */
    .upload-area{border:1.5px dashed rgba(242,138,46,.35);border-radius:12px;background:rgba(242,138,46,.06);padding:20px;text-align:center;cursor:pointer;position:relative;transition:border-color .2s,background .2s}
    .upload-area:hover{border-color:rgba(242,138,46,.6);background:rgba(242,138,46,.1)}
    .upload-area input[type="file"]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
    .upload-icon{font-size:26px;margin-bottom:6px;opacity:.55}
    .upload-text{font-size:13px;color:var(--text-muted);line-height:1.6}
    .upload-text strong{color:var(--amber)}
    .upload-note{font-size:11px;color:rgba(242,175,92,.38);margin-top:4px}
    #preview-wrap{display:none;margin-top:12px;text-align:center}
    #preview-img{max-width:100px;max-height:100px;border-radius:10px;object-fit:cover;border:2px solid rgba(242,138,46,.35);box-shadow:0 8px 24px rgba(38,1,1,.4)}
    #preview-nama{font-size:11px;color:var(--text-muted);margin-top:5px}

    /* Tombol */
    .form-footer{display:flex;align-items:center;gap:12px;margin-top:18px;padding-top:20px;border-top:1px solid rgba(242,138,46,.12)}
    .btn-update{display:inline-flex;align-items:center;gap:8px;padding:12px 26px;background:linear-gradient(135deg,#8B3200 0%,#260101 100%);color:var(--cream);font-family:'Cormorant Garamond',serif;font-size:17px;font-weight:600;letter-spacing:.5px;border:1px solid rgba(242,138,46,.25);border-radius:10px;cursor:pointer;box-shadow:0 4px 20px rgba(38,1,1,.45);transition:transform .15s,box-shadow .15s,filter .15s;position:relative;overflow:hidden}
    .btn-update::after{content:'';position:absolute;top:0;left:-100%;width:60%;height:100%;background:linear-gradient(to right,transparent,rgba(255,255,255,.1),transparent);transform:skewX(-20deg);transition:left .5s ease}
    .btn-update:hover::after{left:160%}
    .btn-update:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(38,1,1,.55);filter:brightness(1.1)}
    .btn-update:active{transform:translateY(0)}
    .btn-batal{display:inline-flex;align-items:center;gap:6px;padding:12px 18px;background:transparent;color:var(--text-muted);font-size:13.5px;border:1px solid rgba(242,138,46,.18);border-radius:10px;cursor:pointer;transition:border-color .2s,color .2s}
    .btn-batal:hover{border-color:rgba(242,138,46,.4);color:var(--amber)}
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

  <!-- MAIN -->
  <main class="main-content">

    <div class="breadcrumb">
      <a href="../dashboard.php">Dashboard</a>
      <span class="breadcrumb-sep">›</span>
      <a href="index.php">Kelola Produk</a>
      <span class="breadcrumb-sep">›</span>
      <span class="breadcrumb-current">Edit: <?= $produk['nama_produk'] ?></span>
    </div>

    <div class="split-card">

      <!-- PANEL KIRI — croissant SVG -->
      <div class="panel-left">
        <div class="deco-circle"></div>
        <div class="deco-circle"></div>
        <div class="deco-circle"></div>

        <div class="bakery-illustration">
          <svg width="190" height="165" viewBox="0 0 190 165" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="95" cy="150" rx="55" ry="8" fill="rgba(38,1,1,0.4)"/>
            <!-- Tanduk kiri -->
            <path d="M 30 118 Q 15 103 22 83 Q 28 66 45 73 Q 38 86 42 98 Q 46 110 55 116 Z" fill="url(#cgL)"/>
            <!-- Tanduk kanan -->
            <path d="M 160 118 Q 175 103 168 83 Q 162 66 145 73 Q 152 86 148 98 Q 144 110 135 116 Z" fill="url(#cgR)"/>
            <!-- Tubuh utama -->
            <path d="M 42 98 Q 50 58 95 50 Q 140 58 148 98 Q 145 126 125 133 Q 95 140 65 133 Q 45 126 42 98 Z" fill="url(#cgM)"/>
            <!-- Lapisan dalam -->
            <path d="M 55 103 Q 60 70 95 63 Q 130 70 135 103 Q 132 123 115 128 Q 95 133 75 128 Q 58 123 55 103 Z" fill="url(#cgI)" opacity="0.7"/>
            <!-- Garis lekukan -->
            <path d="M 62 93 Q 78 73 95 70 Q 112 73 128 93" stroke="rgba(140,60,0,0.4)" stroke-width="1.5" fill="none" stroke-linecap="round"/>
            <path d="M 65 106 Q 80 86 95 83 Q 110 86 125 106" stroke="rgba(140,60,0,0.3)" stroke-width="1" fill="none" stroke-linecap="round"/>
            <!-- Kilap -->
            <ellipse cx="80" cy="76" rx="18" ry="7" fill="rgba(255,255,255,0.16)" transform="rotate(-18 80 76)"/>
            <!-- Taburan tepung -->
            <circle cx="75" cy="88" r="1.5" fill="rgba(255,248,220,0.5)"/>
            <circle cx="88" cy="80" r="1.2" fill="rgba(255,248,220,0.4)"/>
            <circle cx="102" cy="82" r="1.5" fill="rgba(255,248,220,0.5)"/>
            <circle cx="115" cy="90" r="1.2" fill="rgba(255,248,220,0.4)"/>
            <circle cx="95" cy="94" r="1.8" fill="rgba(255,248,220,0.45)"/>
            <!-- Bintang berkedip -->
            <circle cx="150" cy="66" r="2.5" fill="rgba(255,220,140,0.7)">
              <animate attributeName="opacity" values="0.7;0.1;0.7" dur="2s" repeatCount="indefinite"/>
            </circle>
            <circle cx="35" cy="70" r="2" fill="rgba(255,220,140,0.55)">
              <animate attributeName="opacity" values="0.55;0.1;0.55" dur="3s" begin="1s" repeatCount="indefinite"/>
            </circle>
            <circle cx="158" cy="98" r="1.5" fill="rgba(255,220,140,0.4)">
              <animate attributeName="opacity" values="0.4;0.1;0.4" dur="2.5s" begin="0.5s" repeatCount="indefinite"/>
            </circle>
            <defs>
              <linearGradient id="cgM" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#F2AF5C"/>
                <stop offset="35%" stop-color="#D4732A"/>
                <stop offset="100%" stop-color="#8B3200"/>
              </linearGradient>
              <linearGradient id="cgI" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#F2C070"/>
                <stop offset="100%" stop-color="#BF5010"/>
              </linearGradient>
              <linearGradient id="cgL" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#D4732A"/>
                <stop offset="100%" stop-color="#5C1E00"/>
              </linearGradient>
              <linearGradient id="cgR" x1="1" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#D4732A"/>
                <stop offset="100%" stop-color="#5C1E00"/>
              </linearGradient>
            </defs>
          </svg>
        </div>

        <div class="float-shadow"></div>

        <div class="panel-left-text">
          <div class="tagline">Azza Bakery</div>
          <h2>Perbarui<br><em>Informasi</em> Produk</h2>
          <p>Pastikan detail produk selalu akurat dan menarik untuk pelanggan.</p>
        </div>
        <div class="panel-dots"><span></span><span></span><span></span></div>
      </div>

      <!-- PANEL KANAN -->
      <div class="panel-right">

        <div class="form-header">
          <div class="eyebrow">Perbarui Data</div>
          <h1>Edit Produk</h1>
          <p>Mengubah: <strong style="color:var(--amber)"><?= $produk['nama_produk'] ?></strong></p>
        </div>

        <div class="form-divider"></div>

        <!-- Foto saat ini -->
        <?php
          $fp = "../../uploads/" . $produk['gambar'];
          $af = !empty($produk['gambar']) && file_exists($fp);
        ?>
        <div class="foto-saat-ini">
          <?php if ($af) { ?>
            <img src="<?= $fp ?>" alt="Foto saat ini">
          <?php } else { ?>
            <div class="foto-placeholder-kecil">🍞</div>
          <?php } ?>
          <div>
            <div class="foto-info-label">Foto saat ini</div>
            <div class="foto-info-name"><?= !empty($produk['gambar']) ? $produk['gambar'] : 'Belum ada foto' ?></div>
          </div>
        </div>

        <?php if ($error != "") { ?>
          <div class="error-box"><?= $error ?></div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">
          <div class="form-grid">

            <div class="form-group">
              <label>Nama Produk <span class="required">*</span></label>
              <input type="text" name="nama_produk" value="<?= $produk['nama_produk'] ?>" required>
            </div>

            <div class="form-group">
              <label>Harga (Rp) <span class="required">*</span></label>
              <input type="number" name="harga" value="<?= $produk['harga'] ?>" min="0" required>
            </div>

            <div class="form-group">
              <label>Kategori <span class="required">*</span></label>
              <select name="kategori" required>
                <option value="">— Pilih Kategori —</option>
                <option value="Roti Manis" <?= $produk['kategori']=='Roti Manis'?'selected':'' ?>>Roti Manis</option>
                <option value="Kue Kering" <?= $produk['kategori']=='Kue Kering'?'selected':'' ?>>Kue Kering</option>
                <option value="Pastry"     <?= $produk['kategori']=='Pastry'    ?'selected':'' ?>>Pastry</option>
                <option value="Lainnya"    <?= $produk['kategori']=='Lainnya'   ?'selected':'' ?>>Lainnya</option>
              </select>
            </div>

            <div class="form-group">
              <label>Deskripsi Singkat</label>
              <textarea name="deskripsi"><?= $produk['deskripsi'] ?></textarea>
            </div>

            <div class="form-group full">
              <label>Ganti Foto <span style="color:var(--text-muted);letter-spacing:1px;font-weight:400">(Opsional)</span></label>
              <div class="upload-area">
                <input type="file" name="gambar" id="inputFoto" accept=".jpg,.jpeg,.png,.webp" onchange="previewFoto(this)">
                <div class="upload-icon">📷</div>
                <div class="upload-text"><strong>Klik untuk ganti foto</strong> atau biarkan kosong</div>
                <div class="upload-note">Format: JPG, PNG, WEBP · Maksimal 2MB</div>
              </div>
              <div id="preview-wrap">
                <img id="preview-img" src="" alt="Preview">
                <div id="preview-nama"></div>
              </div>
            </div>

          </div>

          <div class="form-footer">
            <button type="submit" name="submit" class="btn-update">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
              Simpan Perubahan
            </button>
            <a href="index.php" class="btn-batal">← Batal</a>
          </div>

        </form>
      </div>

    </div>
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