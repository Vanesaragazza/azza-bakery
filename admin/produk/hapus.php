<?php
// Wajib ada untuk memulai session
session_start ();

// Proteksi halaman
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Panggil file koneksi database
require_once "../../config/koneksi.php";

// Ambil dari URL, jika tidak ada ada tendang ke index
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Ambil data produk dulu, untuk hapus fotonya juga
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id'");

// Kalau produk tidak ditemukan
if (mysqli_num_rows($query) ==0) {
    header("Location: index.php");
    exit();
}

$produk = mysqli_fetch_assoc($query);

// Hapus foto dari folder uploads jika ada
$foto_path = "../../uploads/" . $produk['gambar'];
if (!empty($produk['gambar']) && file_exists($foto_path)) {
    unlink($foto_path); // unlink = hapus file
}

// Hapus data dari database
$hapus = mysqli_query($koneksi, "DELETE FROM produk WHERE id_produk='$id'");

if ($hapus) {

// Berhasil - redirect dengan pesan sukses
header("Location: index.php?pesan=hapus");
} else {
    // Gagal - redirect dengan pesan error
    header("Location:index.php?pesan=gaga");
}
exit();
?>






















?>