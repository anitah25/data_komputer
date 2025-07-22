<?php
// Session handling dan koneksi database bisa ditambahkan di sini
session_start();
// Contoh koneksi database (aktifkan saat sudah memiliki database)
// $conn = mysqli_connect("localhost", "username", "password", "db_komputer_esdm");
// if (!$conn) {
//     die("Koneksi gagal: " . mysqli_connect_error());
// }

// Data dummy untuk demo
$perangkat = [
    [
        'id' => 1,
        'nomor_aset' => 'ESDM-PC-001',
        'nomor_komputer' => 'PC-2023-001',
        'nama_ruangan' => 'Ruang Rapat Utama',
        'nama_pengguna' => 'Budi Santoso',
        'processor' => 'Intel Core i5-12400',
        'ram' => '16GB DDR4',
        'penyimpanan' => 'SSD 512GB',
        'kondisi_komputer' => 'sangat_baik'
    ],
    [
        'id' => 2,
        'nomor_aset' => 'ESDM-PC-002',
        'nomor_komputer' => 'PC-2023-002',
        'nama_ruangan' => 'Ruang Kerja Lt. 3',
        'nama_pengguna' => 'Dewi Lestari',
        'processor' => 'Intel Core i7-11700',
        'ram' => '32GB DDR4',
        'penyimpanan' => 'SSD 1TB',
        'kondisi_komputer' => 'baik'
    ],
    [
        'id' => 3,
        'nomor_aset' => 'ESDM-PC-003',
        'nomor_komputer' => 'PC-2022-015',
        'nama_ruangan' => 'Ruang Server',
        'nama_pengguna' => 'Admin Sistem',
        'processor' => 'Intel Xeon E5-2680',
        'ram' => '64GB ECC DDR4',
        'penyimpanan' => 'SSD 2TB RAID',
        'kondisi_komputer' => 'cukup'
    ],
    [
        'id' => 4,
        'nomor_aset' => 'ESDM-PC-004',
        'nomor_komputer' => 'PC-2021-008',
        'nama_ruangan' => 'Ruang Kerja Lt. 2',
        'nama_pengguna' => 'Rudi Hartono',
        'processor' => 'Intel Core i3-10100',
        'ram' => '8GB DDR4',
        'penyimpanan' => 'HDD 500GB',
        'kondisi_komputer' => 'kurang'
    ],
    [
        'id' => 5,
        'nomor_aset' => 'ESDM-PC-005',
        'nomor_komputer' => 'PC-2019-023',
        'nama_ruangan' => 'Ruang Arsip',
        'nama_pengguna' => 'Siti Aminah',
        'processor' => 'Intel Pentium G5400',
        'ram' => '4GB DDR4',
        'penyimpanan' => 'HDD 320GB',
        'kondisi_komputer' => 'rusak'
    ]
];

// Fungsi untuk menampilkan badge kondisi komputer
function getKondisiBadge($kondisi) {
    switch($kondisi) {
        case 'sangat_baik':
            return '<span class="badge bg-success">Sangat Baik</span>';
        case 'baik':
            return '<span class="badge bg-success">Baik</span>';
        case 'cukup':
            return '<span class="badge bg-warning text-dark">Cukup</span>';
        case 'kurang':
            return '<span class="badge bg-warning text-dark">Kurang</span>';
        case 'rusak':
            return '<span class="badge bg-danger">Rusak</span>';
        default:
            return '<span class="badge bg-secondary">Tidak Diketahui</span>';
    }
}

// Filter komputer dengan kondisi kurang dan rusak
$perangkat_bermasalah = array_filter($perangkat, function($item) {
    return $item['kondisi_komputer'] == 'kurang' || $item['kondisi_komputer'] == 'rusak';
});

// Hitung statistik
$total_perangkat = count($perangkat);
$kondisi_baik = 0;
$kondisi_perlu_perhatian = 0;
$kondisi_rusak = 0;

foreach ($perangkat as $item) {
    if ($item['kondisi_komputer'] == 'sangat_baik' || $item['kondisi_komputer'] == 'baik') {
        $kondisi_baik++;
    } else if ($item['kondisi_komputer'] == 'cukup' || $item['kondisi_komputer'] == 'kurang') {
        $kondisi_perlu_perhatian++;
    } else if ($item['kondisi_komputer'] == 'rusak') {
        $kondisi_rusak++;
    }
}
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
                <h2 class="border-bottom pb-2 mb-4">
                    <i class="bi bi-pie-chart-fill text-primary"></i> Ringkasan Data
                </h2>
            </div>
        </div>
        
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card h-100 shadow-sm border-primary border-top border-4">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h1 class="display-4 text-primary fw-bold mb-0"><?php echo $total_perangkat; ?></h1>
                            <p class="text-muted mt-2">Total Perangkat</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm border-success border-top border-4">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h1 class="display-4 text-success fw-bold mb-0"><?php echo $kondisi_baik; ?></h1>
                            <p class="text-muted mt-2">Kondisi Baik</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm border-warning border-top border-4">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h1 class="display-4 text-warning fw-bold mb-0"><?php echo $kondisi_perlu_perhatian; ?></h1>
                            <p class="text-muted mt-2">Perlu Perhatian</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm border-danger border-top border-4">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h1 class="display-4 text-danger fw-bold mb-0"><?php echo $kondisi_rusak; ?></h1>
                            <p class="text-muted mt-2">Rusak</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning bg-opacity-10">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle text-warning me-2"></i> Perangkat Yang Membutuhkan Perhatian</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($perangkat_bermasalah)): ?>
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                <p class="mt-3 mb-0">Semua perangkat dalam kondisi baik!</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nomor Aset</th>
                                            <th>Nomor Komputer</th>
                                            <th>Ruangan</th>
                                            <th>Pengguna</th>
                                            <th>Spesifikasi</th>
                                            <th>Kondisi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($perangkat_bermasalah as $item): ?>
                                            <tr>
                                                <td>
                                                    <a href="detail-perangkat.php?id=<?php echo $item['id']; ?>" class="text-decoration-none">
                                                        <?php echo htmlspecialchars($item['nomor_aset']); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo htmlspecialchars($item['nomor_komputer']); ?></td>
                                                <td><?php echo htmlspecialchars($item['nama_ruangan']); ?></td>
                                                <td><?php echo htmlspecialchars($item['nama_pengguna']); ?></td>
                                                <td>
                                                    <small>
                                                        <i class="bi bi-cpu"></i> <?php echo htmlspecialchars($item['processor']); ?><br>
                                                        <i class="bi bi-memory"></i> <?php echo htmlspecialchars($item['ram']); ?>
                                                    </small>
                                                </td>
                                                <td><?php echo getKondisiBadge($item['kondisi_komputer']); ?></td>
                                                <td>
                                                    <a href="detail-perangkat.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i> Detail
                                                    </a>
                                                    <?php if(isset($_SESSION['user_id'])): ?>
                                                    <a href="edit-perangkat.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-secondary ms-1">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
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