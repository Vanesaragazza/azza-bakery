<?php
// Wajib ada — mulai session
session_start();

// PROTEKSI HALAMAN — kalau belum login, tendang ke login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Panggil file koneksi database
// "../.." artinya naik 2 folder ke atas (dari produk/ → admin/ → azza_bakery/)
require_once "../../config/koneksi.php";

// ============================================================
// AMBIL SEMUA DATA PRODUK DARI DATABASE
// ORDER BY id_produk DESC = produk terbaru tampil di atas
// ============================================================
$query  = "SELECT * FROM produk ORDER BY id_produk DESC";
$result = mysqli_query($koneksi, $query);

// Hitung total produk
$total  = mysqli_num_rows($result);

// ============================================================
// CEK PESAN SUKSES — dari halaman tambah/edit/hapus
// Kalau ada parameter ?pesan= di URL, tampilkan notifikasi
// ============================================================
$pesan = "";
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'tambah')  $pesan = "✓ Produk berhasil ditambahkan!";
    if ($_GET['pesan'] == 'edit')    $pesan = "✓ Produk berhasil diperbarui!";
    if ($_GET['pesan'] == 'hapus')   $pesan = "✓ Produk berhasil dihapus!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Produk — Azza Bakery Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    /* =============================================
       CSS VARIABLES — sama dengan dashboard
       ============================================= */
    :root {
      --maroon-dark:   #2D0608;
      --maroon:        #6B0F1A;
      --maroon-mid:    #8B1A28;
      --maroon-light:  #B5293D;
      --gold:          #C8952A;
      --gold-light:    #E8B84B;
      --cream:         #FAF6EF;
      --text-dark:     #2D1A0E;
      --text-mid:      #6B4423;
      --text-muted:    #A08060;
      --white:         #FFFFFF;
      --sidebar-w:     260px;
      --shadow-sm:     0 2px 8px rgba(45,6,8,0.08);
      --shadow-md:     0 4px 20px rgba(45,6,8,0.12);
      --success-bg:    #F0FAF4;
      --success-border:#34A853;
    }

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
       SIDEBAR — sama persis dengan dashboard
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

    .sidebar-logo {
      padding: 28px 24px 24px;
      border-bottom: 1px solid rgba(200,149,42,0.2);
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .sidebar-logo img {
      width: 52px; height: 52px;
      border-radius: 10px;
      object-fit: cover;
      border: 2px solid rgba(200,149,42,0.4);
    }

    .sidebar-logo-text .brand {
      font-family: 'Cormorant Garamond', serif;
      font-size: 20px; font-weight: 700;
      color: var(--white); line-height: 1.1;
    }

    .sidebar-logo-text .brand span { color: var(--gold-light); }

    .sidebar-logo-text .sub {
      font-size: 10px; font-weight: 300;
      color: rgba(255,235,235,0.45);
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-top: 2px;
    }

    .sidebar-admin {
      padding: 20px 24px;
      border-bottom: 1px solid rgba(200,149,42,0.15);
    }

    .admin-badge { display: flex; align-items: center; gap: 12px; }

    .admin-avatar {
      width: 38px; height: 38px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--gold), var(--maroon-mid));
      display: flex; align-items: center; justify-content: center;
      font-family: 'Cormorant Garamond', serif;
      font-size: 16px; font-weight: 700;
      color: white; flex-shrink: 0;
    }

    .admin-info .name { font-size: 13.5px; font-weight: 500; color: var(--white); }
    .admin-info .role { font-size: 11px; color: rgba(255,235,235,0.45); margin-top: 1px; }

    .sidebar-nav { padding: 20px 16px; flex: 1; }

    .nav-label {
      font-size: 10px; font-weight: 600;
      color: rgba(255,235,235,0.3);
      letter-spacing: 2.5px; text-transform: uppercase;
      padding: 0 8px; margin-bottom: 8px;
    }

    .nav-item {
      display: flex; align-items: center; gap: 12px;
      padding: 11px 12px; border-radius: 10px;
      margin-bottom: 4px; font-size: 14px; font-weight: 400;
      color: rgba(255,235,235,0.65); cursor: pointer;
      transition: background .2s, color .2s;
    }

    .nav-item:hover { background: rgba(200,149,42,0.12); color: var(--gold-light); }

    /* Menu Kelola Produk aktif karena sedang di halaman ini */
    .nav-item.active {
      background: linear-gradient(135deg, rgba(200,149,42,0.2), rgba(200,149,42,0.08));
      color: var(--gold-light); font-weight: 500;
      border: 1px solid rgba(200,149,42,0.2);
    }

    .nav-icon { width: 18px; height: 18px; opacity: 0.7; flex-shrink: 0; }
    .nav-item.active .nav-icon, .nav-item:hover .nav-icon { opacity: 1; }

    .sidebar-logout { padding: 16px; border-top: 1px solid rgba(200,149,42,0.15); }

    .btn-logout {
      display: flex; align-items: center;
      justify-content: center; gap: 8px;
      width: 100%; padding: 11px;
      background: rgba(181,41,61,0.15);
      border: 1px solid rgba(181,41,61,0.3);
      border-radius: 10px; color: #FFAAB5;
      font-family: 'DM Sans', sans-serif;
      font-size: 13.5px; font-weight: 500;
      cursor: pointer;
      transition: background .2s, border-color .2s;
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
      flex: 1; padding: 36px 40px;
      min-height: 100vh;
    }

    /* Header halaman */
    .page-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 28px;
    }

    .page-header-left .title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 34px; font-weight: 600;
      color: var(--maroon); line-height: 1.1;
    }

    .page-header-left .subtitle {
      font-size: 14px; color: var(--text-muted);
      margin-top: 6px;
    }

    .header-line {
      width: 48px; height: 2px;
      background: linear-gradient(to right, var(--gold), transparent);
      margin-top: 14px; border-radius: 2px;
    }

    /* Tombol Tambah Produk */
    .btn-tambah {
      display: inline-flex;
      align-items: center; gap: 8px;
      padding: 12px 22px;
      background: linear-gradient(135deg, var(--maroon-light), var(--maroon));
      color: white;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px; font-weight: 500;
      border-radius: 10px;
      box-shadow: 0 4px 16px rgba(107,15,26,0.35);
      transition: transform .15s, box-shadow .15s;
      flex-shrink: 0;
      margin-top: 4px;
    }

    .btn-tambah:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(107,15,26,0.4);
    }

    /* =============================================
       NOTIFIKASI SUKSES
       ============================================= */
    .notif-sukses {
      background: var(--success-bg);
      border: 1px solid var(--success-border);
      border-left: 4px solid var(--success-border);
      border-radius: 10px;
      padding: 14px 18px;
      margin-bottom: 24px;
      font-size: 14px;
      color: #1A6B35;
      font-weight: 500;
      /* Animasi muncul dari atas */
      animation: slideDown .3s ease;
    }

    @keyframes slideDown {
      from { opacity:0; transform: translateY(-10px); }
      to   { opacity:1; transform: translateY(0); }
    }

    /* =============================================
       TABEL PRODUK
       ============================================= */
    .table-card {
      background: var(--white);
      border-radius: 16px;
      box-shadow: var(--shadow-sm);
      border: 1px solid rgba(200,149,42,0.1);
      overflow: hidden;
    }

    /* Info jumlah produk di atas tabel */
    .table-info {
      padding: 16px 24px;
      border-bottom: 1px solid rgba(200,149,42,0.1);
      font-size: 13px;
      color: var(--text-muted);
    }

    .table-info strong { color: var(--maroon); }

    table { width: 100%; border-collapse: collapse; }

    thead tr {
      background: linear-gradient(135deg, var(--maroon-dark), var(--maroon));
    }

    thead th {
      padding: 14px 20px;
      text-align: left;
      font-size: 11px; font-weight: 600;
      color: rgba(255,235,235,0.75);
      letter-spacing: 1.5px; text-transform: uppercase;
    }

    tbody tr {
      border-bottom: 1px solid rgba(200,149,42,0.08);
      transition: background .15s;
    }

    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(200,149,42,0.04); }

    tbody td { padding: 14px 20px; font-size: 14px; }

    /* Kolom foto produk */
    .foto-produk {
      width: 52px; height: 52px;
      border-radius: 8px;
      object-fit: cover;
      border: 1px solid rgba(200,149,42,0.2);
    }

    /* Placeholder kalau foto tidak ada */
    .foto-placeholder {
      width: 52px; height: 52px;
      border-radius: 8px;
      background: linear-gradient(135deg, rgba(200,149,42,0.1), rgba(107,15,26,0.1));
      display: flex; align-items: center; justify-content: center;
      font-size: 20px;
      border: 1px solid rgba(200,149,42,0.15);
    }

    .nama-produk { font-weight: 500; color: var(--text-dark); }
    .deskripsi-singkat {
      font-size: 12px; color: var(--text-muted);
      margin-top: 2px;
      /* Batasi teks maksimal 1 baris */
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 220px;
    }

    /* Badge kategori */
    .badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11.5px; font-weight: 500;
    }

    .badge-roti   { background: rgba(200,149,42,0.12); color: var(--gold); }
    .badge-kue    { background: rgba(107,15,26,0.1);   color: var(--maroon-light); }
    .badge-pastry { background: rgba(45,6,8,0.08);     color: var(--text-mid); }
    .badge-lainnya{ background: rgba(160,128,96,0.12); color: var(--text-muted); }

    .harga { font-weight: 600; color: var(--maroon); }

    /* Tombol aksi Edit & Hapus */
    .aksi-wrap { display: flex; gap: 8px; align-items: center; }

    .btn-edit, .btn-hapus {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 6px 14px;
      border-radius: 7px;
      font-size: 12.5px; font-weight: 500;
      transition: background .2s, transform .15s;
      cursor: pointer;
      border: none;
    }

    .btn-edit {
      background: rgba(200,149,42,0.12);
      color: var(--gold);
    }

    .btn-edit:hover {
      background: rgba(200,149,42,0.22);
      transform: translateY(-1px);
    }

    .btn-hapus {
      background: rgba(181,41,61,0.1);
      color: var(--maroon-light);
    }

    .btn-hapus:hover {
      background: rgba(181,41,61,0.2);
      transform: translateY(-1px);
    }

    /* Kalau tidak ada produk */
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: var(--text-muted);
    }

    .empty-state .empty-icon { font-size: 48px; margin-bottom: 16px; opacity: 0.4; }
    .empty-state p { font-size: 15px; margin-bottom: 16px; }

    .btn-tambah-empty {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 11px 22px;
      background: linear-gradient(135deg, var(--maroon-light), var(--maroon));
      color: white; border-radius: 10px;
      font-size: 14px; font-weight: 500;
    }

    /* Footer */
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

    <div class="sidebar-logo">
      <img src="../../uploads/logo_azza.jpg" alt="Logo Azza Bakery">
      <div class="sidebar-logo-text">
        <div class="brand">Azza <span>Bakery</span></div>
        <div class="sub">Admin Panel</div>
      </div>
    </div>

    <div class="sidebar-admin">
      <div class="admin-badge">
        <div class="admin-avatar">
          <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
        </div>
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

      <!-- Kelola Produk — aktif -->
      <a href="index.php" class="nav-item active">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        Kelola Produk
      </a>

      <!-- Kelola Artikel -->
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

  <!-- =============================================
       KONTEN UTAMA
       ============================================= -->
  <main class="main-content">

    <!-- Header halaman -->
    <div class="page-header">
      <div class="page-header-left">
        <div class="title">Kelola Produk</div>
        <div class="subtitle">Tambah, edit, atau hapus produk di katalog Azza Bakery</div>
        <div class="header-line"></div>
      </div>
      <!-- Tombol tambah produk baru -->
      <a href="tambah.php" class="btn-tambah">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <line x1="12" y1="5" x2="12" y2="19"/>
          <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Produk
      </a>
    </div>

    <!-- Notifikasi sukses — muncul setelah tambah/edit/hapus -->
    <?php if ($pesan != "") { ?>
      <div class="notif-sukses"><?= $pesan ?></div>
    <?php } ?>

    <!-- TABEL DAFTAR PRODUK -->
    <div class="table-card">

      <div class="table-info">
        Total: <strong><?= $total ?> produk</strong> terdaftar di katalog
      </div>

      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>

          <?php
          if (mysqli_num_rows($result) > 0) {
            $no = 1;
            // LOOP — tampilkan setiap produk satu per satu
            while ($row = mysqli_fetch_assoc($result)) {

              // Tentukan class badge berdasarkan kategori
              $badge = 'badge-lainnya';
              if ($row['kategori'] == 'Roti Manis') $badge = 'badge-roti';
              if ($row['kategori'] == 'Kue Kering') $badge = 'badge-kue';
              if ($row['kategori'] == 'Pastry')     $badge = 'badge-pastry';

              // Cek apakah foto produk ada di folder uploads
              $foto_path = "../../uploads/" . $row['gambar'];
              $ada_foto  = !empty($row['gambar']) && file_exists($foto_path);
          ?>
            <tr>
              <td><?= $no++ ?></td>

              <!-- Kolom foto — tampilkan foto kalau ada, placeholder kalau tidak -->
              <td>
                <?php if ($ada_foto) { ?>
                  <img src="<?= $foto_path ?>" alt="<?= $row['nama_produk'] ?>" class="foto-produk">
                <?php } else { ?>
                  <div class="foto-placeholder">🍞</div>
                <?php } ?>
              </td>

              <!-- Kolom nama & deskripsi singkat -->
              <td>
                <div class="nama-produk"><?= $row['nama_produk'] ?></div>
                <div class="deskripsi-singkat"><?= $row['deskripsi'] ?></div>
              </td>

              <!-- Kolom kategori dengan badge warna -->
              <td>
                <span class="badge <?= $badge ?>"><?= $row['kategori'] ?></span>
              </td>

              <!-- Kolom harga dengan format Rupiah -->
              <td class="harga">
                Rp <?= number_format($row['harga'], 0, ',', '.') ?>
              </td>

              <!-- Kolom aksi: Edit & Hapus -->
              <td>
                <div class="aksi-wrap">
                  <!-- Tombol Edit — kirim id_produk ke edit.php -->
                  <a href="edit.php?id=<?= $row['id_produk'] ?>" class="btn-edit">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit
                  </a>

                  <!-- Tombol Hapus — kirim id_produk ke hapus.php -->
                  <!-- onclick confirm() = minta konfirmasi sebelum hapus -->
                  <a href="hapus.php?id=<?= $row['id_produk'] ?>"
                     class="btn-hapus"
                     onclick="return confirm('Yakin mau hapus produk \'<?= $row['nama_produk'] ?>\'? Tindakan ini tidak bisa dibatalkan!')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3 6 5 6 21 6"/>
                      <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                      <path d="M10 11v6M14 11v6"/>
                      <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                    </svg>
                    Hapus
                  </a>
                </div>
              </td>

            </tr>
          <?php
            } // end while
          } else {
          ?>
            <!-- Tampilan kalau belum ada produk sama sekali -->
            <tr>
              <td colspan="6">
                <div class="empty-state">
                  <div class="empty-icon">🍞</div>
                  <p>Belum ada produk di katalog.</p>
                  <a href="tambah.php" class="btn-tambah-empty">+ Tambah Produk Pertama</a>
                </div>
              </td>
            </tr>
          <?php } ?>

        </tbody>
      </table>
    </div>

    <div class="page-footer">
      &copy; <?= date('Y') ?> Azza Bakery Admin System &nbsp;·&nbsp; v1.0
    </div>

  </main>

</body>
</html>