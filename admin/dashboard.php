<?php
// Wajib ada di paling atas — mulai session
session_start();

// PROTEKSI HALAMAN — cek apakah admin sudah login
// Kalau belum ada session username, tendang balik ke login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Panggil file koneksi database
require_once "../config/koneksi.php";

// ============================================================
// AMBIL DATA STATISTIK DARI DATABASE
// Fungsi COUNT(*) menghitung total baris di sebuah tabel
// ============================================================

// Hitung total produk di tabel produk
$query_produk   = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM produk");
$row_produk     = mysqli_fetch_assoc($query_produk);
$total_produk   = $row_produk['total'];

// Ambil 5 produk terbaru untuk ditampilkan di tabel bawah
$query_terbaru  = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id_produk DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Azza Bakery Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    /* =============================================
       CSS VARIABLES — palet warna Azza Bakery
       ============================================= */
    :root {
      --maroon-dark:   #2D0608;
      --maroon:        #6B0F1A;
      --maroon-mid:    #8B1A28;
      --maroon-light:  #B5293D;
      --gold:          #C8952A;
      --gold-light:    #E8B84B;
      --cream:         #FAF6EF;
      --cream-dark:    #F0E8DC;
      --text-dark:     #2D1A0E;
      --text-mid:      #6B4423;
      --text-muted:    #A08060;
      --white:         #FFFFFF;
      --sidebar-w:     260px;
      --shadow-sm:     0 2px 8px rgba(45,6,8,0.08);
      --shadow-md:     0 4px 20px rgba(45,6,8,0.12);
    }

    /* =============================================
       RESET & BASE
       ============================================= */
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--cream);
      color: var(--text-dark);
      display: flex;
      min-height: 100vh;
    }

    a { text-decoration: none; color: inherit; }

    /* =============================================
       SIDEBAR
       ============================================= */
    .sidebar {
      width: var(--sidebar-w);
      background: var(--maroon-dark);
      min-height: 100vh;
      position: fixed;
      left: 0; top: 0;
      display: flex;
      flex-direction: column;
      z-index: 100;
      box-shadow: 4px 0 20px rgba(45,6,8,0.25);
    }

    /* Logo area di atas sidebar */
    .sidebar-logo {
      padding: 28px 24px 24px;
      border-bottom: 1px solid rgba(200,149,42,0.2);
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .sidebar-logo img {
      width: 52px;
      height: 52px;
      border-radius: 10px;
      object-fit: cover;
      border: 2px solid rgba(200,149,42,0.4);
    }

    .sidebar-logo-text .brand {
      font-family: 'Cormorant Garamond', serif;
      font-size: 20px;
      font-weight: 700;
      color: var(--white);
      line-height: 1.1;
    }

    .sidebar-logo-text .brand span { color: var(--gold-light); }

    .sidebar-logo-text .sub {
      font-size: 10px;
      font-weight: 300;
      color: rgba(255,235,235,0.45);
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-top: 2px;
    }

    /* Info admin di sidebar */
    .sidebar-admin {
      padding: 20px 24px;
      border-bottom: 1px solid rgba(200,149,42,0.15);
    }

    .admin-badge {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    /* Lingkaran inisial admin */
    .admin-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--gold), var(--maroon-mid));
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Cormorant Garamond', serif;
      font-size: 16px;
      font-weight: 700;
      color: white;
      flex-shrink: 0;
    }

    .admin-info .name {
      font-size: 13.5px;
      font-weight: 500;
      color: var(--white);
    }

    .admin-info .role {
      font-size: 11px;
      color: rgba(255,235,235,0.45);
      margin-top: 1px;
    }

    /* Menu navigasi sidebar */
    .sidebar-nav {
      padding: 20px 16px;
      flex: 1;
    }

    .nav-label {
      font-size: 10px;
      font-weight: 600;
      color: rgba(255,235,235,0.3);
      letter-spacing: 2.5px;
      text-transform: uppercase;
      padding: 0 8px;
      margin-bottom: 8px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 12px;
      border-radius: 10px;
      margin-bottom: 4px;
      font-size: 14px;
      font-weight: 400;
      color: rgba(255,235,235,0.65);
      cursor: pointer;
      transition: background .2s, color .2s;
    }

    .nav-item:hover {
      background: rgba(200,149,42,0.12);
      color: var(--gold-light);
    }

    /* Style untuk menu yang sedang aktif */
    .nav-item.active {
      background: linear-gradient(135deg, rgba(200,149,42,0.2), rgba(200,149,42,0.08));
      color: var(--gold-light);
      font-weight: 500;
      border: 1px solid rgba(200,149,42,0.2);
    }

    /* Ikon di menu — menggunakan emoji SVG sederhana */
    .nav-icon {
      width: 18px;
      height: 18px;
      opacity: 0.7;
      flex-shrink: 0;
    }

    .nav-item.active .nav-icon,
    .nav-item:hover .nav-icon { opacity: 1; }

    /* Tombol logout di bawah sidebar */
    .sidebar-logout {
      padding: 16px;
      border-top: 1px solid rgba(200,149,42,0.15);
    }

    .btn-logout {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      padding: 11px;
      background: rgba(181,41,61,0.15);
      border: 1px solid rgba(181,41,61,0.3);
      border-radius: 10px;
      color: #FFAAB5;
      font-family: 'DM Sans', sans-serif;
      font-size: 13.5px;
      font-weight: 500;
      cursor: pointer;
      transition: background .2s, border-color .2s;
      text-decoration: none;
    }

    .btn-logout:hover {
      background: rgba(181,41,61,0.28);
      border-color: rgba(181,41,61,0.5);
    }

    /* =============================================
       KONTEN UTAMA
       ============================================= */
    .main-content {
      margin-left: var(--sidebar-w);
      flex: 1;
      padding: 36px 40px;
      min-height: 100vh;
    }

    /* Header halaman */
    .page-header {
      margin-bottom: 32px;
    }

    .page-header .greeting {
      font-family: 'Cormorant Garamond', serif;
      font-size: 34px;
      font-weight: 600;
      color: var(--maroon);
      line-height: 1.1;
    }

    .page-header .greeting span { color: var(--gold); }

    .page-header .subtext {
      font-size: 14px;
      color: var(--text-muted);
      margin-top: 6px;
    }

    /* Garis dekoratif di bawah header */
    .header-line {
      width: 48px;
      height: 2px;
      background: linear-gradient(to right, var(--gold), transparent);
      margin-top: 14px;
      border-radius: 2px;
    }

    /* =============================================
       STAT CARDS
       ============================================= */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
      margin-bottom: 36px;
    }

    .stat-card {
      background: var(--white);
      border-radius: 16px;
      padding: 28px;
      box-shadow: var(--shadow-sm);
      border: 1px solid rgba(200,149,42,0.12);
      position: relative;
      overflow: hidden;
      transition: transform .2s, box-shadow .2s;
    }

    .stat-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-md);
    }

    /* Garis aksen kiri card */
    .stat-card::before {
      content: '';
      position: absolute;
      left: 0; top: 0; bottom: 0;
      width: 4px;
      background: linear-gradient(to bottom, var(--gold), var(--maroon-light));
      border-radius: 4px 0 0 4px;
    }

    .stat-label {
      font-size: 11px;
      font-weight: 600;
      color: var(--text-muted);
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-bottom: 12px;
    }

    .stat-number {
      font-family: 'Cormorant Garamond', serif;
      font-size: 52px;
      font-weight: 700;
      color: var(--maroon);
      line-height: 1;
      margin-bottom: 8px;
    }

    .stat-desc {
      font-size: 13px;
      color: var(--text-muted);
    }

    /* Ikon besar di pojok kanan card */
    .stat-icon {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 48px;
      opacity: 0.07;
    }

    /* =============================================
       TABEL PRODUK TERBARU
       ============================================= */
    .section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }

    .section-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 22px;
      font-weight: 600;
      color: var(--maroon);
    }

    /* Tombol "Lihat Semua" di header section */
    .btn-see-all {
      font-size: 13px;
      font-weight: 500;
      color: var(--gold);
      border: 1px solid rgba(200,149,42,0.35);
      padding: 7px 16px;
      border-radius: 8px;
      transition: background .2s;
    }

    .btn-see-all:hover {
      background: rgba(200,149,42,0.08);
    }

    /* Container tabel */
    .table-card {
      background: var(--white);
      border-radius: 16px;
      box-shadow: var(--shadow-sm);
      border: 1px solid rgba(200,149,42,0.1);
      overflow: hidden;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead tr {
      background: linear-gradient(135deg, var(--maroon-dark), var(--maroon));
    }

    thead th {
      padding: 14px 20px;
      text-align: left;
      font-size: 11px;
      font-weight: 600;
      color: rgba(255,235,235,0.75);
      letter-spacing: 1.5px;
      text-transform: uppercase;
    }

    tbody tr {
      border-bottom: 1px solid rgba(200,149,42,0.08);
      transition: background .15s;
    }

    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(200,149,42,0.04); }

    tbody td {
      padding: 14px 20px;
      font-size: 14px;
      color: var(--text-dark);
    }

    /* Badge kategori produk */
    .badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11.5px;
      font-weight: 500;
    }

    .badge-roti {
      background: rgba(200,149,42,0.12);
      color: var(--gold);
    }

    .badge-kue {
      background: rgba(107,15,26,0.1);
      color: var(--maroon-light);
    }

    .badge-pastry {
      background: rgba(45,6,8,0.08);
      color: var(--text-mid);
    }

    .badge-lainnya {
      background: rgba(160,128,96,0.12);
      color: var(--text-muted);
    }

    /* Harga produk */
    .harga {
      font-weight: 500;
      color: var(--maroon);
    }

    /* Tombol aksi di tabel */
    .btn-edit {
      display: inline-block;
      padding: 5px 12px;
      background: rgba(200,149,42,0.1);
      color: var(--gold);
      border-radius: 6px;
      font-size: 12px;
      font-weight: 500;
      margin-right: 6px;
      transition: background .2s;
    }

    .btn-edit:hover { background: rgba(200,149,42,0.2); }

    /* Kalau tabel kosong */
    .empty-state {
      text-align: center;
      padding: 40px;
      color: var(--text-muted);
      font-size: 14px;
    }

    /* =============================================
       FOOTER KECIL
       ============================================= */
    .page-footer {
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px solid rgba(200,149,42,0.12);
      font-size: 12px;
      color: var(--text-muted);
      text-align: center;
    }
  </style>
</head>
<body>

  <!-- =============================================
       SIDEBAR
       ============================================= -->
  <aside class="sidebar">

    <!-- Logo Azza Bakery -->
    <div class="sidebar-logo">
      <img src="../uploads/logo_azza.jpg" alt="Logo Azza Bakery">
      <div class="sidebar-logo-text">
        <div class="brand">Azza <span>Bakery</span></div>
        <div class="sub">Admin Panel</div>
      </div>
    </div>

    <!-- Info admin yang sedang login -->
    <div class="sidebar-admin">
      <div class="admin-badge">
        <div class="admin-avatar">
          <!-- Ambil huruf pertama username untuk avatar -->
          <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
        </div>
        <div class="admin-info">
          <div class="name"><?= $_SESSION['username'] ?></div>
          <div class="role">Administrator</div>
        </div>
      </div>
    </div>

    <!-- Menu navigasi -->
    <nav class="sidebar-nav">
      <div class="nav-label">Menu Utama</div>

      <!-- Dashboard — aktif karena sedang di halaman ini -->
      <a href="dashboard.php" class="nav-item active">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/>
          <rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        Dashboard
      </a>

      <!-- Kelola Produk -->
      <a href="produk/index.php" class="nav-item">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        Kelola Produk
      </a>

      <!-- Kelola Artikel -->
<a href="artikel/index.php" class="nav-item">
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

    <!-- Tombol Logout -->
    <div class="sidebar-logout">
      <a href="logout.php" class="btn-logout">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Logout
      </a>
    </div>

  </aside>

  <!-- =============================================
       KONTEN UTAMA
       ============================================= -->
  <main class="main-content">

    <!-- Header halaman -->
    <div class="page-header">
      <div class="greeting">
        Selamat Datang, <span><?= $_SESSION['username'] ?>!</span>
      </div>
      <div class="subtext">
        Pantau dan kelola toko roti kamu dari sini. &nbsp;·&nbsp;
        <?= date('l, d F Y') ?>
      </div>
      <div class="header-line"></div>
    </div>

    <!-- STAT CARDS — ringkasan data -->
    <div class="stats-grid">

      <!-- Card Total Produk -->
      <div class="stat-card">
        <div class="stat-label">Total Menu Roti</div>
        <div class="stat-number"><?= $total_produk ?></div>
        <div class="stat-desc">Produk terdaftar di katalog</div>
        <div class="stat-icon">🍞</div>
      </div>

      <!-- Card Pesanan Hari Ini — placeholder, bisa dikembangkan -->
      <div class="stat-card">
        <div class="stat-label">Pesanan Masuk</div>
        <div class="stat-number">—</div>
        <div class="stat-desc">Via WhatsApp Admin</div>
        <div class="stat-icon">💬</div>
      </div>

    </div>

    <!-- TABEL PRODUK TERBARU -->
    <div class="section-header">
      <div class="section-title">Produk Terbaru</div>
      <a href="produk/index.php" class="btn-see-all">Lihat Semua →</a>
    </div>

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>

          <?php
          // Cek apakah ada data produk
          if (mysqli_num_rows($query_terbaru) > 0) {
            $no = 1;
            // Loop — tampilkan satu per satu data produk
            while ($produk = mysqli_fetch_assoc($query_terbaru)) {
              
              // Tentukan warna badge berdasarkan kategori
              $badge_class = 'badge-lainnya';
              if ($produk['kategori'] == 'Roti Manis')  $badge_class = 'badge-roti';
              if ($produk['kategori'] == 'Kue Kering')  $badge_class = 'badge-kue';
              if ($produk['kategori'] == 'Pastry')      $badge_class = 'badge-pastry';
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $produk['nama_produk'] ?></td>
              <td>
                <span class="badge <?= $badge_class ?>">
                  <?= $produk['kategori'] ?>
                </span>
              </td>
              <td class="harga">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></td>
              <td>
                <a href="produk/edit.php?id=<?= $produk['id_produk'] ?>" class="btn-edit">
                  Edit
                </a>
              </td>
            </tr>
          <?php
            } // end while
          } else {
          ?>
            <tr>
              <td colspan="5" class="empty-state">
                Belum ada produk. 
                <a href="produk/tambah.php" style="color: var(--gold);">Tambah sekarang →</a>
              </td>
            </tr>
          <?php } ?>

        </tbody>
      </table>
    </div>

    <!-- Footer -->
    <div class="page-footer">
      &copy; <?= date('Y') ?> Azza Bakery Admin System &nbsp;·&nbsp; v1.0
    </div>

  </main>

</body>
</html>