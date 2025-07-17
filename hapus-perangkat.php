<?php
// Session handling dan koneksi database bisa ditambahkan di sini
session_start();

// Cek apakah user sudah login
if(!isset($_SESSION['user_id'])) {
    // Set pesan bahwa akses ditolak
    $_SESSION['error_message'] = "Anda harus login untuk menghapus perangkat.";
    // Redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Contoh koneksi database (aktifkan saat sudah memiliki database)
// $conn = mysqli_connect("localhost", "username", "password", "db_komputer_esdm");
// if (!$conn) {
//     die("Koneksi gagal: " . mysqli_connect_error());
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        
        // Proses hapus data dari database (aktifkan saat sudah memiliki database)
        /*
        $sql = "DELETE FROM perangkat WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success_message'] = "Data perangkat berhasil dihapus";
        } else {
            $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
        }
        */
        
        // Simulasi sukses untuk demo
        $_SESSION['success_message'] = "Data perangkat berhasil dihapus";
    } else {
        $_SESSION['error_message'] = "ID perangkat tidak ditemukan";
    }
}

// Redirect kembali ke halaman daftar perangkat
header("Location: daftar-perangkat.php");
exit();
?>
