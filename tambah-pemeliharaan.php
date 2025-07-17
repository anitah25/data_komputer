<?php
// Session handling dan koneksi database
session_start();
// Contoh koneksi database (aktifkan saat sudah memiliki database)
// $conn = mysqli_connect("localhost", "username", "password", "db_komputer_esdm");
// if (!$conn) {
//     die("Koneksi gagal: " . mysqli_connect_error());
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_perangkat = $_POST['id_perangkat'] ?? 0;
    $tanggal = $_POST['tanggal'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';
    $petugas = $_POST['petugas'] ?? '';
    
    if ($id_perangkat && $tanggal && $keterangan && $petugas) {
        // Format tanggal untuk display (DD/MM/YYYY)
        $tanggal_display = date('d/m/Y', strtotime($tanggal));
        
        // Simpan data ke database (aktifkan saat sudah memiliki database)
        /*
        $sql = "INSERT INTO pemeliharaan (id_perangkat, tanggal, keterangan, petugas) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isss", $id_perangkat, $tanggal, $keterangan, $petugas);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_message'] = "Catatan pemeliharaan berhasil ditambahkan";
        } else {
            $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
        }
        
        mysqli_stmt_close($stmt);
        */
        
        // Simulasi sukses untuk demo
        $_SESSION['success_message'] = "Catatan pemeliharaan berhasil ditambahkan";
    } else {
        $_SESSION['error_message'] = "Semua field harus diisi";
    }
    
    // Redirect kembali ke halaman detail perangkat
    header("Location: detail-perangkat.php?id=$id_perangkat");
    exit();
} else {
    // Jika bukan request POST, redirect ke halaman daftar perangkat
    header("Location: daftar-perangkat.php");
    exit();
}
?>
