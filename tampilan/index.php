<?php
// Session handling dan koneksi database bisa ditambahkan di sini
session_start();
// Contoh koneksi database (aktifkan saat sudah memiliki database)
// $conn = mysqli_connect("localhost", "username", "password", "db_komputer_esdm");
// if (!$conn) {
//     die("Koneksi gagal: " . mysqli_connect_error());
// }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda | Sistem Informasi Komputer ESDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include 'includes/navbar.php'; ?>

    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold text-primary mb-3">Sistem Informasi Komputer ESDM</h1>
                <p class="lead">Selamat datang di Sistem Informasi Pengelolaan Data Komputer Kementerian Energi dan Sumber Daya Mineral. Sistem ini memudahkan pengelolaan aset teknologi informasi secara terpusat.</p>
                <div class="d-grid gap-2 d-md-flex mt-4">
                    <a href="tambah-perangkat.php" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-plus-circle"></i> Tambah Data Perangkat
                    </a>
                    <a href="daftar-perangkat.php" class="btn btn-outline-primary btn-lg px-4">
                        <i class="bi bi-list-ul"></i> Lihat Daftar Perangkat
                    </a>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <img src="https://via.placeholder.com/600x400/0d6efd/ffffff?text=SI-KOMPUTER+ESDM" alt="Sistem Informasi Komputer" class="img-fluid rounded shadow">
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Fitur Utama</h2>
            </div>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3">
                            <i class="bi bi-pc-display" style="font-size: 2rem;"></i>
                        </div>
                        <h3>Pengelolaan Aset</h3>
                        <p>Kelola data perangkat komputer dengan mudah, termasuk spesifikasi, pengguna, dan kondisi terkini.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3">
                            <i class="bi bi-clipboard-data" style="font-size: 2rem;"></i>
                        </div>
                        <h3>Pemeliharaan</h3>
                        <p>Pantau dan kelola histori pemeliharaan perangkat untuk memastikan kinerja yang optimal.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3">
                            <i class="bi bi-bar-chart" style="font-size: 2rem;"></i>
                        </div>
                        <h3>Laporan & Analisis</h3>
                        <p>Buat laporan dan analisis untuk pengambilan keputusan terkait pengelolaan aset IT.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>

    <style>
    .feature-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    </style>
</body>
</html>
