<?php
session_start();

// Cek apakah admin sudah login?
// Jika tidak ada session 'username', berarti dia penyusup!
if (!isset($_SESSION['username'])) {
    // Tendang balik ke halaman login
    header("Location: login.php");
    exit();
}

// Jika sampai sini, berarti admin sah.
?>
<div class="welcome-message">
    <h2>Selamat Datang, <?php echo $_SESSION['username']; ?>!</h2>
    <p>Hari ini mau update menu roti apa di Azza Bakery?</p>
</div>