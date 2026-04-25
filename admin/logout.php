<?php
session_start();
session_unset(); // Hapus semua variabel session
session_destroy(); // Hancurkan session-nya

// Balikkan ke login
header("Location: login.php");
exit();
?>