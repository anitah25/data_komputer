<?php
// Session handling dan koneksi database bisa ditambahkan di sini
session_start();
// Contoh koneksi database (aktifkan saat sudah memiliki database)
// $conn = mysqli_connect("localhost", "username", "password", "db_komputer_esdm");
// if (!$conn) {
//     die("Koneksi gagal: " . mysqli_connect_error());
// }

// Ambil data perangkat dari database (aktifkan saat sudah memiliki database)
$perangkat = [];
/*
$sql = "SELECT * FROM perangkat ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if ($result) {
    while($row = mysqli_fetch_assoc($result)) {
        $perangkat[] = $row;
    }
}
*/

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

// Ambil filter jika ada
$filter_ruangan = $_GET['filter_ruangan'] ?? '';
$filter_kondisi = $_GET['filter_kondisi'] ?? '';

// Terapkan filter
if (!empty($filter_ruangan) || !empty($filter_kondisi)) {
    $filtered_perangkat = [];
    foreach ($perangkat as $item) {
        $include = true;
        
        if (!empty($filter_ruangan) && stripos($item['nama_ruangan'], $filter_ruangan) === false) {
            $include = false;
        }
        
        if (!empty($filter_kondisi) && $item['kondisi_komputer'] != $filter_kondisi) {
            $include = false;
        }
        
        if ($include) {
            $filtered_perangkat[] = $item;
        }
    }
    $perangkat = $filtered_perangkat;
}

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
    <title>Daftar Perangkat | Sistem Informasi Komputer ESDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables@1.10.18/media/css/jquery.dataTables.min.css">
</head>
<body class="bg-light">
    <?php include 'includes/navbar.php'; ?>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="border-bottom pb-2">
                    <i class="bi bi-list-ul text-primary"></i> Daftar Perangkat Komputer
                </h2>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end align-items-center">
                <?php if(isset($_SESSION['user_id'])): ?>
                <a href="tambah-perangkat.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Perangkat Baru
                </a>
                <?php else: ?>
                <a href="login.php" class="btn btn-outline-primary">
                    <i class="bi bi-box-arrow-in-right"></i> Login untuk Mengelola Data
                </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Cari perangkat...">
                                <button class="btn btn-primary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-3 justify-content-md-end filter-container">
                                <div class="dropdown-select">
                                    <select class="form-select filter-select" id="filterRuangan" name="filter_ruangan" style="min-width: 160px;">
                                        <option value="">Semua Ruangan</option>
                                        <option value="Ruang Rapat" <?php echo ($filter_ruangan == 'Ruang Rapat') ? 'selected' : ''; ?>>Ruang Rapat</option>
                                        <option value="Ruang Server" <?php echo ($filter_ruangan == 'Ruang Server') ? 'selected' : ''; ?>>Ruang Server</option>
                                        <option value="Ruang Kerja" <?php echo ($filter_ruangan == 'Ruang Kerja') ? 'selected' : ''; ?>>Ruang Kerja</option>
                                    </select>
                                </div>
                                <div class="dropdown-select">
                                    <select class="form-select filter-select" id="filterKondisi" name="filter_kondisi" style="min-width: 160px;">
                                    <option value="">Semua Kondisi</option>
                                    <option value="sangat_baik" <?php echo ($filter_kondisi == 'sangat_baik') ? 'selected' : ''; ?>>Sangat Baik</option>
                                    <option value="baik" <?php echo ($filter_kondisi == 'baik') ? 'selected' : ''; ?>>Baik</option>
                                    <option value="cukup" <?php echo ($filter_kondisi == 'cukup') ? 'selected' : ''; ?>>Cukup</option>
                                    <option value="kurang" <?php echo ($filter_kondisi == 'kurang') ? 'selected' : ''; ?>>Kurang</option>
                                    <option value="rusak" <?php echo ($filter_kondisi == 'rusak') ? 'selected' : ''; ?>>Rusak</option>
                                </select>
                                </div>
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover shadow-sm" id="perangkatTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
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
                            <?php if(empty($perangkat)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data perangkat yang ditemukan</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($perangkat as $index => $item): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td>
                                            <a href="detail-perangkat.php?id=<?php echo $item['id']; ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($item['nomor_aset']); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="detail-perangkat.php?id=<?php echo $item['id']; ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($item['nomor_komputer']); ?>
                                            </a>
                                        </td>
                                        <td><?php echo htmlspecialchars($item['nama_ruangan']); ?></td>
                                        <td><?php echo htmlspecialchars($item['nama_pengguna']); ?></td>
                                        <td>
                                            <small>
                                                <i class="bi bi-cpu"></i> <?php echo htmlspecialchars($item['processor']); ?><br>
                                                <i class="bi bi-memory"></i> <?php echo htmlspecialchars($item['ram']); ?><br>
                                                <i class="bi bi-hdd"></i> <?php echo htmlspecialchars($item['penyimpanan']); ?>
                                            </small>
                                        </td>
                                        <td><?php echo getKondisiBadge($item['kondisi_komputer']); ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Aksi
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="detail-perangkat.php?id=<?php echo $item['id']; ?>"><i class="bi bi-eye"></i> Detail</a></li>
                                                    <?php if(isset($_SESSION['user_id'])): ?>
                                                    <li><a class="dropdown-item" href="edit-perangkat.php?id=<?php echo $item['id']; ?>"><i class="bi bi-pencil"></i> Edit</a></li>
                                                    <?php endif; ?>
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#barcodeModal<?php echo $item['id']; ?>"><i class="bi bi-upc-scan"></i> Lihat Barcode</a></li>
                                                    <?php if(isset($_SESSION['user_id'])): ?>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $item['id']; ?>"><i class="bi bi-trash"></i> Hapus</a></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                            
                                            <!-- Modal Barcode -->
                                            <div class="modal fade" id="barcodeModal<?php echo $item['id']; ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Barcode <?php echo htmlspecialchars($item['nomor_aset']); ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <div class="barcode-container mb-3">
                                                                <!-- PHP to generate barcode, or use JS -->
                                                                <canvas class="barcode-canvas" data-asset="<?php echo $item['nomor_aset']; ?>"></canvas>
                                                            </div>
                                                            <button type="button" class="btn btn-outline-primary btn-sm print-barcode">
                                                                <i class="bi bi-printer"></i> Cetak Barcode
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Modal Delete -->
                                            <div class="modal fade" id="deleteModal<?php echo $item['id']; ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-danger">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus data perangkat dengan nomor aset <strong><?php echo htmlspecialchars($item['nomor_aset']); ?></strong>?</p>
                                                            <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <form action="hapus-perangkat.php" method="POST">
                                                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="bi bi-trash"></i> Hapus Perangkat
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan 1-<?php echo count($perangkat); ?> dari <?php echo count($perangkat); ?> perangkat
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Sebelumnya</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item disabled">
                                <a class="page-link" href="#">Selanjutnya</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-pie-chart-fill text-primary"></i> Ringkasan Data</h5>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="text-primary"><?php echo $total_perangkat; ?></h3>
                                    <p class="mb-0">Total Perangkat</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="text-success"><?php echo $kondisi_baik; ?></h3>
                                    <p class="mb-0">Kondisi Baik</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="text-warning"><?php echo $kondisi_perlu_perhatian; ?></h3>
                                    <p class="mb-0">Perlu Perhatian</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="text-danger"><?php echo $kondisi_rusak; ?></h3>
                                    <p class="mb-0">Rusak</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-4 mt-md-0">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> Ekspor Data</h5>
                        <p>Unduh data perangkat dalam berbagai format</p>
                        <div class="d-flex gap-2">
                            <a href="export.php?type=excel" class="btn btn-outline-primary">
                                <i class="bi bi-file-earmark-excel"></i> Excel
                            </a>
                            <a href="export.php?type=pdf" class="btn btn-outline-primary">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a>
                            <a href="export.php?type=csv" class="btn btn-outline-primary">
                                <i class="bi bi-file-earmark-text"></i> CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables@1.10.18/media/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="assets/js/script.js"></script>
    
    <script>
        $(document).ready(function() {
            // Simple search functionality
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#perangkatTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            
            // Memastikan dropdown filter memiliki ruang yang cukup
            function adjustFilterDropdowns() {
                $('.filter-select').each(function() {
                    var textWidth = $(this).find('option:selected').text().length * 8; // Perkiraan lebar teks
                    var minWidth = Math.max(textWidth + 40, 160); // Tambahkan ruang untuk ikon (minimal 160px)
                    $(this).css('min-width', minWidth + 'px');
                });
            }
            
            // Panggil saat halaman dimuat dan saat dropdown berubah
            adjustFilterDropdowns();
            $('.filter-select').on('change', adjustFilterDropdowns);
            
            // Generate barcodes for all barcode canvases
            $('.barcode-canvas').each(function() {
                var assetNumber = $(this).data('asset');
                JsBarcode(this, assetNumber, {
                    format: "CODE128",
                    lineColor: "#000",
                    width: 2,
                    height: 100,
                    displayValue: true,
                    fontSize: 18,
                    margin: 10
                });
            });
            
            // Handle print barcode button
            $('.print-barcode').click(function() {
                var canvas = $(this).closest('.modal-body').find('canvas')[0];
                var win = window.open();
                win.document.write('<html><head><title>Print Barcode</title>');
                win.document.write('<style>body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }</style>');
                win.document.write('</head><body>');
                win.document.write('<img src="' + canvas.toDataURL() + '" style="max-width: 100%;">');
                win.document.write('</body></html>');
                win.document.close();
                win.print();
                win.close();
            });
        });
    </script>
</body>
</html>
