<?php
// Session handling dan koneksi database bisa ditambahkan di sini
session_start();

// Cek apakah user sudah login
if(!isset($_SESSION['user_id'])) {
    // Set pesan bahwa akses ditolak
    $_SESSION['error_message'] = "Anda harus login untuk mengedit perangkat.";
    // Redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Contoh koneksi database (aktifkan saat sudah memiliki database)
// $conn = mysqli_connect("localhost", "username", "password", "db_komputer_esdm");
// if (!$conn) {
//     die("Koneksi gagal: " . mysqli_connect_error());
// }

// Cek apakah ada parameter ID
if (!isset($_GET['id'])) {
    header("Location: daftar-perangkat.php");
    exit();
}

$id = $_GET['id'];

// Ambil data perangkat berdasarkan ID (aktifkan saat sudah memiliki database)
/*
$sql = "SELECT * FROM perangkat WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error_message'] = "Perangkat tidak ditemukan";
    header("Location: daftar-perangkat.php");
    exit();
}

$perangkat = mysqli_fetch_assoc($result);
*/

// Data dummy untuk demo
$perangkat = [
    'id' => $id,
    'nomor_aset' => 'ESDM-PC-00' . $id,
    'nomor_komputer' => 'PC-2023-00' . $id,
    'nama_ruangan' => 'Ruang Rapat Utama',
    'nama_pengguna' => 'Budi Santoso',
    'penggunaan_sekarang' => 'Admin',
    'tahun_pengadaan' => '2023',
    'processor' => 'Intel Core i5-12400',
    'ram' => '16GB DDR4',
    'penyimpanan' => 'SSD 512GB',
    'vga' => 'Intel UHD Graphics 730',
    'kondisi_komputer' => 'sangat_baik',
    'keterangan' => 'Perangkat dalam kondisi baik dan terawat'
];

// Proses form jika ada request POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    $namaRuangan = $_POST['namaRuangan'] ?? '';
    $nomorAset = $_POST['nomorAset'] ?? '';
    $nomorKomputer = $_POST['nomorKomputer'] ?? '';
    $tahunPengadaan = $_POST['tahunPengadaan'] ?? '';
    $namaPengguna = $_POST['namaPengguna'] ?? '';
    $penggunaanSekarang = $_POST['penggunaanSekarang'] ?? '';
    $processor = $_POST['processor'] ?? '';
    $ram = $_POST['ram'] ?? '';
    $penyimpanan = $_POST['penyimpanan'] ?? '';
    $vga = $_POST['vga'] ?? '';
    $kondisiKomputer = $_POST['kondisiKomputer'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';
    
    // Proses upload gambar jika ada
    $targetFile = "";
    if (isset($_FILES["gambarPerangkat"]) && $_FILES["gambarPerangkat"]["error"] == 0) {
        $uploadDir = "uploads/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $targetFile = $uploadDir . basename($_FILES["gambarPerangkat"]["name"]);
        move_uploaded_file($_FILES["gambarPerangkat"]["tmp_name"], $targetFile);
    }
    
    // Update data ke database (aktifkan saat sudah memiliki database)
    /*
    $sql = "UPDATE perangkat SET 
            nama_ruangan = '$namaRuangan',
            nomor_aset = '$nomorAset',
            nomor_komputer = '$nomorKomputer',
            tahun_pengadaan = '$tahunPengadaan',
            nama_pengguna = '$namaPengguna',
            penggunaan_sekarang = '$penggunaanSekarang',
            processor = '$processor',
            ram = '$ram',
            penyimpanan = '$penyimpanan',
            vga = '$vga',
            kondisi_komputer = '$kondisiKomputer',
            keterangan = '$keterangan'";
            
    if(!empty($targetFile)) {
        $sql .= ", gambar_path = '$targetFile'";
    }
    
    $sql .= " WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Data perangkat berhasil diperbarui";
        header("Location: daftar-perangkat.php");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
    */
    
    // Simulasi update sukses untuk demo
    $_SESSION['success_message'] = "Data perangkat berhasil diperbarui";
    header("Location: daftar-perangkat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Perangkat | Sistem Informasi Komputer ESDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <?php include 'includes/navbar.php'; ?>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="border-bottom pb-2">
                    <i class="bi bi-pencil-square text-primary"></i> Edit Data Perangkat
                </h2>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end align-items-center">
                <a href="daftar-perangkat.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <?php if(isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4 class="mb-3">Informasi Umum</h4>
                            
                            <div class="mb-3">
                                <label for="namaRuangan" class="form-label">Nama Ruangan</label>
                                <input type="text" class="form-control" id="namaRuangan" name="namaRuangan" value="<?php echo htmlspecialchars($perangkat['nama_ruangan']); ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nomorAset" class="form-label">Nomor Aset</label>
                                        <input type="text" class="form-control" id="nomorAset" name="nomorAset" value="<?php echo htmlspecialchars($perangkat['nomor_aset']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nomorKomputer" class="form-label">Nomor Komputer</label>
                                        <input type="text" class="form-control" id="nomorKomputer" name="nomorKomputer" value="<?php echo htmlspecialchars($perangkat['nomor_komputer']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="tahunPengadaan" class="form-label">Tahun Pengadaan</label>
                                <input type="text" class="form-control" id="tahunPengadaan" name="tahunPengadaan" value="<?php echo htmlspecialchars($perangkat['tahun_pengadaan']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="namaPengguna" class="form-label">Nama Pengguna</label>
                                <input type="text" class="form-control" id="namaPengguna" name="namaPengguna" value="<?php echo htmlspecialchars($perangkat['nama_pengguna']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="penggunaanSekarang" class="form-label">Penggunaan Saat Ini</label>
                                <input type="text" class="form-control" id="penggunaanSekarang" name="penggunaanSekarang" value="<?php echo htmlspecialchars($perangkat['penggunaan_sekarang']); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h4 class="mb-3">Spesifikasi Perangkat</h4>
                            
                            <div class="mb-3">
                                <label for="processor" class="form-label">Processor</label>
                                <input type="text" class="form-control" id="processor" name="processor" value="<?php echo htmlspecialchars($perangkat['processor']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="ram" class="form-label">RAM</label>
                                <input type="text" class="form-control" id="ram" name="ram" value="<?php echo htmlspecialchars($perangkat['ram']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="penyimpanan" class="form-label">Penyimpanan</label>
                                <input type="text" class="form-control" id="penyimpanan" name="penyimpanan" value="<?php echo htmlspecialchars($perangkat['penyimpanan']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="vga" class="form-label">VGA / Graphics Card</label>
                                <input type="text" class="form-control" id="vga" name="vga" value="<?php echo htmlspecialchars($perangkat['vga']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="kondisiKomputer" class="form-label">Kondisi Komputer</label>
                                <select class="form-select" id="kondisiKomputer" name="kondisiKomputer" required>
                                    <option value="sangat_baik" <?php echo ($perangkat['kondisi_komputer'] == 'sangat_baik') ? 'selected' : ''; ?>>Sangat Baik</option>
                                    <option value="baik" <?php echo ($perangkat['kondisi_komputer'] == 'baik') ? 'selected' : ''; ?>>Baik</option>
                                    <option value="cukup" <?php echo ($perangkat['kondisi_komputer'] == 'cukup') ? 'selected' : ''; ?>>Cukup</option>
                                    <option value="kurang" <?php echo ($perangkat['kondisi_komputer'] == 'kurang') ? 'selected' : ''; ?>>Kurang</option>
                                    <option value="rusak" <?php echo ($perangkat['kondisi_komputer'] == 'rusak') ? 'selected' : ''; ?>>Rusak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo htmlspecialchars($perangkat['keterangan']); ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gambarPerangkat" class="form-label">Gambar Perangkat (Opsional)</label>
                                <input class="form-control" type="file" id="gambarPerangkat" name="gambarPerangkat" accept="image/*">
                                <div class="form-text">Unggah gambar baru untuk mengganti yang lama. Biarkan kosong jika tidak ingin mengubah.</div>
                                <?php if(isset($perangkat['gambar_path']) && !empty($perangkat['gambar_path'])): ?>
                                <div class="mt-2">
                                    <p>Gambar saat ini:</p>
                                    <img src="<?php echo htmlspecialchars($perangkat['gambar_path']); ?>" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="bi bi-x-circle"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Batal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin membatalkan perubahan? Semua perubahan yang belum disimpan akan hilang.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <a href="daftar-perangkat.php" class="btn btn-danger">Ya, Batalkan</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview gambar yang diupload
        document.getElementById('gambarPerangkat').addEventListener('change', function() {
            const preview = document.createElement('img');
            preview.classList.add('img-thumbnail', 'mt-2');
            preview.style.maxHeight = '150px';
            
            const file = this.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                
                // Hapus preview lama jika ada
                const previewContainer = this.parentNode;
                const oldPreview = previewContainer.querySelector('img.img-thumbnail:not([src^="uploads/"])');
                if (oldPreview) {
                    previewContainer.removeChild(oldPreview);
                }
                
                previewContainer.appendChild(preview);
            }
        });
    </script>
</body>
</html>
