<?php 
// koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "db_azza_bakery";

// Perintah untuk mengkoneksikan ke database
$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("koneksi gagal: " . mysqli_connect_error());
} 
