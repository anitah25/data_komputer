<?php
// Session handling dan koneksi database
session_start();
// Contoh koneksi database (aktifkan saat sudah memiliki database)
// $conn = mysqli_connect("localhost", "username", "password", "db_komputer_esdm");
// if (!$conn) {
//     die("Koneksi gagal: " . mysqli_connect_error());
// }

// Ambil ID perangkat dari parameter URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    // Redirect jika ID tidak valid
    header("Location: daftar-perangkat.php");
    exit();
}

// Ambil data perangkat dari database berdasarkan ID (aktifkan saat sudah memiliki database)
/*
$sql = "SELECT * FROM perangkat WHERE id = $id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $perangkat = mysqli_fetch_assoc($result);
} else {
    // Perangkat tidak ditemukan
    $_SESSION['error_message'] = "Perangkat tidak ditemukan";
    header("Location: daftar-perangkat.php");
    exit();
}

// Ambil histori pemeliharaan perangkat
$histori = [];
$sql = "SELECT * FROM pemeliharaan WHERE id_perangkat = $id ORDER BY tanggal DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while($row = mysqli_fetch_assoc($result)) {
        $histori[] = $row;
    }
}
*/

// Data dummy untuk demo
$perangkat_dummy = [
    1 => [
        'id' => 1,
        'nomor_aset' => 'ESDM-PC-001',
        'nomor_komputer' => 'PC-2023-001',
        'nama_ruangan' => 'Ruang Rapat Utama',
        'nama_pengguna' => 'Budi Santoso',
        'tahun_pengadaan' => '2023',
        'processor' => 'Intel Core i5-12400',
        'ram' => '16GB DDR4',
        'vga' => 'Intel UHD Graphics 730',
        'penyimpanan' => 'SSD 512GB',
        'sistem_operasi' => 'Windows 11 Pro',
        'penggunaan_sekarang' => 'Presentasi dan video conference',
        'kesesuaian_pc' => 'sangat_sesuai',
        'kondisi_komputer' => 'sangat_baik',
        'detail_kondisi' => 'Perangkat dalam kondisi sangat baik, semua fitur berfungsi dengan normal',
        'histori_pemeliharaan' => '12/03/2024 - Pembersihan internal dan update sistem operasi',
        'foto_path' => 'https://via.placeholder.com/800x600/0d6efd/ffffff?text=PC-2023-001',
        'tanggal_terakhir_update' => '2024-05-10'
    ],
    2 => [
        'id' => 2,
        'nomor_aset' => 'ESDM-PC-002',
        'nomor_komputer' => 'PC-2023-002',
        'nama_ruangan' => 'Ruang Kerja Lt. 3',
        'nama_pengguna' => 'Dewi Lestari',
        'tahun_pengadaan' => '2023',
        'processor' => 'Intel Core i7-11700',
        'ram' => '32GB DDR4',
        'vga' => 'NVIDIA GeForce RTX 3050',
        'penyimpanan' => 'SSD 1TB',
        'sistem_operasi' => 'Windows 10 Pro',
        'penggunaan_sekarang' => 'Desain grafis dan pengolahan data',
        'kesesuaian_pc' => 'sesuai',
        'kondisi_komputer' => 'baik',
        'detail_kondisi' => 'Perangkat berfungsi dengan baik, terdapat sedikit goresan pada casing',
        'histori_pemeliharaan' => '05/01/2024 - Penggantian kipas pendingin\n10/03/2024 - Update driver VGA',
        'foto_path' => 'https://via.placeholder.com/800x600/0d6efd/ffffff?text=PC-2023-002',
        'tanggal_terakhir_update' => '2024-03-15'
    ],
    3 => [
        'id' => 3,
        'nomor_aset' => 'ESDM-PC-003',
        'nomor_komputer' => 'PC-2022-015',
        'nama_ruangan' => 'Ruang Server',
        'nama_pengguna' => 'Admin Sistem',
        'tahun_pengadaan' => '2022',
        'processor' => 'Intel Xeon E5-2680',
        'ram' => '64GB ECC DDR4',
        'vga' => 'NVIDIA Quadro P2000',
        'penyimpanan' => 'SSD 2TB RAID',
        'sistem_operasi' => 'Windows Server 2019',
        'penggunaan_sekarang' => 'Server database dan aplikasi internal',
        'kesesuaian_pc' => 'sesuai',
        'kondisi_komputer' => 'cukup',
        'detail_kondisi' => 'Beberapa port USB tidak berfungsi, performa masih baik',
        'histori_pemeliharaan' => '02/12/2023 - Penambahan RAM dari 32GB menjadi 64GB\n15/01/2024 - Pembaruan sistem operasi\n20/02/2024 - Perbaikan sistem pendingin',
        'foto_path' => 'https://via.placeholder.com/800x600/0d6efd/ffffff?text=PC-2022-015',
        'tanggal_terakhir_update' => '2024-02-25'
    ]
];

// Gunakan data dummy
if (isset($perangkat_dummy[$id])) {
    $perangkat = $perangkat_dummy[$id];
    
    // Format histori pemeliharaan untuk ditampilkan sebagai list
    $histori_list = [];
    if (!empty($perangkat['histori_pemeliharaan'])) {
        $histori_list = explode("\n", $perangkat['histori_pemeliharaan']);
    }
} else {
    // Perangkat tidak ditemukan
    $_SESSION['error_message'] = "Perangkat tidak ditemukan";
    header("Location: daftar-perangkat.php");
    exit();
}

// Fungsi untuk menampilkan label kesesuaian PC
function getKesesuaianLabel($kesesuaian) {
    switch($kesesuaian) {
        case 'sangat_sesuai':
            return '<span class="badge bg-success">Sangat Sesuai</span>';
        case 'sesuai':
            return '<span class="badge bg-success">Sesuai</span>';
        case 'kurang_sesuai':
            return '<span class="badge bg-warning text-dark">Kurang Sesuai</span>';
        case 'tidak_sesuai':
            return '<span class="badge bg-danger">Tidak Sesuai</span>';
        default:
            return '<span class="badge bg-secondary">Tidak Diketahui</span>';
    }
}

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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Perangkat | Sistem Informasi Komputer ESDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .detail-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .spec-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-right: 15px;
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
        }
        
        .spec-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .spec-item:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline-item {
            position: relative;
            padding-bottom: 20px;
            padding-left: 15px;
        }
        
        .timeline-item:before {
            content: "";
            position: absolute;
            left: -30px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: var(--primary-color);
            z-index: 1;
        }
        
        .timeline-item:after {
            content: "";
            position: absolute;
            left: -23px;
            top: 16px;
            height: 100%;
            width: 2px;
            background-color: #dee2e6;
        }
        
        .timeline-item:last-child:after {
            display: none;
        }
        
        .action-btn {
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        .detail-header {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .detail-header:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background-color: var(--primary-color);
        }
        
        .device-image-container {
            height: 300px;
            overflow: hidden;
            border-radius: 12px;
            position: relative;
        }
        
        .device-image {
            object-fit: cover;
            width: 100%;
            height: 100%;
            transition: transform 0.5s ease;
        }
        
        .device-image-container:hover .device-image {
            transform: scale(1.05);
        }
        
        .qr-container {
            padding: 15px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'includes/navbar.php'; ?>

    <div class="container py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="daftar-perangkat.php">Daftar Perangkat</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Perangkat</li>
                    </ol>
                </nav>
                <h2 class="mb-0">
                    <i class="bi bi-pc-display text-primary"></i> 
                    <?php echo htmlspecialchars($perangkat['nomor_komputer']); ?>
                </h2>
                <p class="text-muted"><?php echo htmlspecialchars($perangkat['nomor_aset']); ?></p>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
                <div class="btn-group" role="group">
                    <a href="edit-perangkat.php?id=<?php echo $id; ?>" class="btn btn-outline-primary action-btn">
                        <i class="bi bi-pencil"></i> Edit Data
                    </a>
                    <a href="#" class="btn btn-outline-primary action-btn" data-bs-toggle="modal" data-bs-target="#qrModal">
                        <i class="bi bi-qr-code"></i> QR Code
                    </a>
                    <a href="#" class="btn btn-outline-primary action-btn" data-bs-toggle="modal" data-bs-target="#printModal">
                        <i class="bi bi-printer"></i> Cetak
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="device-image-container shadow-sm mb-4">
                    <img src="<?php echo $perangkat['foto_path']; ?>" alt="<?php echo htmlspecialchars($perangkat['nomor_komputer']); ?>" class="device-image">
                    <div class="position-absolute bottom-0 end-0 p-3">
                        <span class="badge rounded-pill bg-dark bg-opacity-75">
                            <i class="bi bi-camera"></i> Foto Perangkat
                        </span>
                    </div>
                </div>

                <div class="card detail-card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="bi bi-info-circle text-primary"></i> Informasi Umum</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-door-open"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Ruangan</small>
                                        <strong><?php echo htmlspecialchars($perangkat['nama_ruangan']); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Pengguna</small>
                                        <strong><?php echo htmlspecialchars($perangkat['nama_pengguna']); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-calendar-event"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Tahun Pengadaan</small>
                                        <strong><?php echo htmlspecialchars($perangkat['tahun_pengadaan']); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Kesesuaian Mendukung Pekerjaan</small>
                                        <?php echo getKesesuaianLabel($perangkat['kesesuaian_pc']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="bi bi-activity"></i>
                            </div>
                            <div class="w-100">
                                <small class="text-muted d-block">Penggunaan Sekarang</small>
                                <strong><?php echo htmlspecialchars($perangkat['penggunaan_sekarang']); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card detail-card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="bi bi-cpu text-primary"></i> Spesifikasi Teknis</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-cpu"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Processor</small>
                                        <strong><?php echo htmlspecialchars($perangkat['processor']); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-memory"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">RAM</small>
                                        <strong><?php echo htmlspecialchars($perangkat['ram']); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-gpu-card"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">VGA</small>
                                        <strong><?php echo htmlspecialchars($perangkat['vga']); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-hdd"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Penyimpanan</small>
                                        <strong><?php echo htmlspecialchars($perangkat['penyimpanan']); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-windows"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Sistem Operasi</small>
                                        <strong><?php echo htmlspecialchars($perangkat['sistem_operasi']); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <div class="spec-icon">
                                        <i class="bi bi-heart-pulse"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Kondisi</small>
                                        <?php echo getKondisiBadge($perangkat['kondisi_komputer']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if(!empty($perangkat['detail_kondisi'])): ?>
                        <div class="alert alert-light mt-3">
                            <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Detail Kondisi:</h6>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($perangkat['detail_kondisi'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card detail-card sticky-md-top mb-4" style="top: 20px; z-index: 1;">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="bi bi-upc-scan text-primary"></i> Identifikasi</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="qr-container mb-3">
                            <canvas id="barcodeCanvas"></canvas>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-outline-primary action-btn me-2" id="printBarcode">
                                <i class="bi bi-printer"></i> Cetak Barcode
                            </button>
                            <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="modal" data-bs-target="#qrModal">
                                <i class="bi bi-qr-code"></i> QR Code
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card detail-card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="bi bi-clock-history text-primary"></i> Histori Pemeliharaan</h4>
                    </div>
                    <div class="card-body">
                        <?php if(empty($histori_list)): ?>
                            <p class="text-muted text-center">Belum ada data pemeliharaan</p>
                        <?php else: ?>
                            <div class="timeline">
                                <?php foreach($histori_list as $item): ?>
                                    <div class="timeline-item">
                                        <p class="mb-0"><?php echo htmlspecialchars($item); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-3">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tambahPemeliharaanModal">
                                    <i class="bi bi-plus-circle"></i> Tambah Catatan Pemeliharaan
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card detail-card">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="bi bi-file-earmark-text text-primary"></i> Informasi Tambahan</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Terakhir diperbarui</span>
                            <span><?php echo date('d M Y', strtotime($perangkat['tanggal_terakhir_update'])); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Usia perangkat</span>
                            <span><?php echo date('Y') - intval($perangkat['tahun_pengadaan']); ?> tahun</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4 mb-2">
            <a href="daftar-perangkat.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Perangkat
            </a>
            <div>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash"></i> Hapus Perangkat
                </button>
            </div>
        </div>
    </div>
    
    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">QR Code Perangkat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="qrcode" class="d-inline-block p-3 bg-white"></div>
                    <p class="mt-3">Scan QR code ini untuk melihat detail perangkat</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" id="printQr">
                        <i class="bi bi-printer"></i> Cetak QR Code
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Print Modal -->
    <div class="modal fade" id="printModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cetak Informasi Perangkat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" id="printFullDetail">
                            <div>
                                <h6 class="mb-1">Detail Lengkap</h6>
                                <small class="text-muted">Semua informasi perangkat</small>
                            </div>
                            <i class="bi bi-file-earmark-text"></i>
                        </button>
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" id="printSpecOnly">
                            <div>
                                <h6 class="mb-1">Spesifikasi Teknis</h6>
                                <small class="text-muted">Hanya informasi spesifikasi perangkat</small>
                            </div>
                            <i class="bi bi-cpu"></i>
                        </button>
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" id="printHistoryOnly">
                            <div>
                                <h6 class="mb-1">Histori Pemeliharaan</h6>
                                <small class="text-muted">Riwayat pemeliharaan perangkat</small>
                            </div>
                            <i class="bi bi-clock-history"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tambah Pemeliharaan Modal -->
    <div class="modal fade" id="tambahPemeliharaanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Catatan Pemeliharaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="tambah-pemeliharaan.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_perangkat" value="<?php echo $id; ?>">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                            <div class="form-text">Masukkan keterangan pemeliharaan yang dilakukan</div>
                        </div>
                        <div class="mb-3">
                            <label for="petugas" class="form-label">Petugas</label>
                            <input type="text" class="form-control" id="petugas" name="petugas" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data perangkat dengan nomor aset <strong><?php echo htmlspecialchars($perangkat['nomor_aset']); ?></strong>?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="hapus-perangkat.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Hapus Perangkat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.0/build/qrcode.min.js"></script>
    <script src="assets/js/script.js"></script>
    
    <script>
        $(document).ready(function() {
            // Generate barcode
            JsBarcode("#barcodeCanvas", "<?php echo $perangkat['nomor_aset']; ?>", {
                format: "CODE128",
                lineColor: "#0d6efd",
                width: 2,
                height: 80,
                displayValue: true,
                fontSize: 16,
                margin: 10
            });
            
            // Generate QR code
            var qrURL = window.location.origin + window.location.pathname + "?id=<?php echo $id; ?>";
            var qrcode = new QRCode(document.getElementById("qrcode"), {
                text: qrURL,
                width: 200,
                height: 200,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
            
            // Print barcode
            $("#printBarcode").click(function() {
                var canvas = document.getElementById("barcodeCanvas");
                printCanvas(canvas, "Barcode - <?php echo $perangkat['nomor_aset']; ?>");
            });
            
            // Print QR code
            $("#printQr").click(function() {
                var qrElement = document.getElementById("qrcode");
                printElement(qrElement, "QR Code - <?php echo $perangkat['nomor_aset']; ?>");
            });
            
            // Print functions for barcode and QR code
            function printCanvas(canvas, title) {
                var win = window.open();
                win.document.write('<html><head><title>' + title + '</title>');
                win.document.write('<style>body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }</style>');
                win.document.write('</head><body>');
                win.document.write('<img src="' + canvas.toDataURL() + '" style="max-width: 100%;">');
                win.document.write('</body></html>');
                win.document.close();
                win.print();
                win.close();
            }
            
            function printElement(element, title) {
                var win = window.open();
                win.document.write('<html><head><title>' + title + '</title>');
                win.document.write('<style>body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }</style>');
                win.document.write('</head><body>');
                win.document.write(element.innerHTML);
                win.document.write('</body></html>');
                win.document.close();
                win.print();
                win.close();
            }
            
            // Print functions for details
            $("#printFullDetail, #printSpecOnly, #printHistoryOnly").click(function() {
                var win = window.open();
                win.document.write('<html><head><title>Detail Perangkat</title>');
                win.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
                win.document.write('<style>body { padding: 20px; }</style>');
                win.document.write('</head><body>');
                
                // Header
                win.document.write('<div class="text-center mb-4"><h3>Detail Perangkat</h3>');
                win.document.write('<p>Nomor Aset: <?php echo htmlspecialchars($perangkat['nomor_aset']); ?> | Nomor Komputer: <?php echo htmlspecialchars($perangkat['nomor_komputer']); ?></p></div>');
                
                // Content based on which button was clicked
                if (this.id === "printFullDetail" || this.id === "printSpecOnly") {
                    win.document.write('<div class="card mb-4"><div class="card-header"><h5>Spesifikasi Teknis</h5></div><div class="card-body">');
                    win.document.write('<table class="table table-bordered"><tbody>');
                    win.document.write('<tr><th>Processor</th><td><?php echo htmlspecialchars($perangkat['processor']); ?></td></tr>');
                    win.document.write('<tr><th>RAM</th><td><?php echo htmlspecialchars($perangkat['ram']); ?></td></tr>');
                    win.document.write('<tr><th>VGA</th><td><?php echo htmlspecialchars($perangkat['vga']); ?></td></tr>');
                    win.document.write('<tr><th>Penyimpanan</th><td><?php echo htmlspecialchars($perangkat['penyimpanan']); ?></td></tr>');
                    win.document.write('<tr><th>Sistem Operasi</th><td><?php echo htmlspecialchars($perangkat['sistem_operasi']); ?></td></tr>');
                    win.document.write('</tbody></table></div></div>');
                }
                
                if (this.id === "printFullDetail") {
                    win.document.write('<div class="card mb-4"><div class="card-header"><h5>Informasi Umum</h5></div><div class="card-body">');
                    win.document.write('<table class="table table-bordered"><tbody>');
                    win.document.write('<tr><th>Ruangan</th><td><?php echo htmlspecialchars($perangkat['nama_ruangan']); ?></td></tr>');
                    win.document.write('<tr><th>Pengguna</th><td><?php echo htmlspecialchars($perangkat['nama_pengguna']); ?></td></tr>');
                    win.document.write('<tr><th>Tahun Pengadaan</th><td><?php echo htmlspecialchars($perangkat['tahun_pengadaan']); ?></td></tr>');
                    win.document.write('<tr><th>Penggunaan Sekarang</th><td><?php echo htmlspecialchars($perangkat['penggunaan_sekarang']); ?></td></tr>');
                    win.document.write('<tr><th>Kondisi</th><td><?php echo htmlspecialchars($perangkat['kondisi_komputer']); ?></td></tr>');
                    win.document.write('<tr><th>Detail Kondisi</th><td><?php echo nl2br(htmlspecialchars($perangkat['detail_kondisi'])); ?></td></tr>');
                    win.document.write('</tbody></table></div></div>');
                }
                
                if (this.id === "printFullDetail" || this.id === "printHistoryOnly") {
                    win.document.write('<div class="card"><div class="card-header"><h5>Histori Pemeliharaan</h5></div><div class="card-body">');
                    
                    <?php if(empty($histori_list)): ?>
                        win.document.write('<p class="text-muted text-center">Belum ada data pemeliharaan</p>');
                    <?php else: ?>
                        win.document.write('<ul class="list-group">');
                        <?php foreach($histori_list as $item): ?>
                            win.document.write('<li class="list-group-item"><?php echo htmlspecialchars($item); ?></li>');
                        <?php endforeach; ?>
                        win.document.write('</ul>');
                    <?php endif; ?>
                    
                    win.document.write('</div></div>');
                }
                
                // Footer
                win.document.write('<div class="text-center mt-4"><p class="small text-muted">Dicetak pada: ' + new Date().toLocaleString() + '</p></div>');
                
                win.document.write('</body></html>');
                win.document.close();
                win.print();
                win.close();
            });
        });
    </script>
</body>
</html>
