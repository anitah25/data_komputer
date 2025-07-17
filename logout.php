<?php
session_start();

// Hapus semua data session
$_SESSION = array();

// Hapus cookie session jika ada
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Hancurkan session
session_destroy();

// Set pesan sukses dalam URL
$_SESSION['message'] = "Anda berhasil keluar dari sistem";

// Redirect ke halaman login dengan pesan sukses
header("Location: login.php?logout=success");
exit();
?>
