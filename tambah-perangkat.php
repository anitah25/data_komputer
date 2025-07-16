<?php
// Session handling dan koneksi database bisa ditambahkan di sini
session_start();
// Contoh koneksi database (aktifkan saat sudah memiliki database)
// $conn = mysqli_connect("localhost", "username", "password", "db_komputer_esdm");
// if (!$conn) {
//     die("Koneksi gagal: " . mysqli_connect_error());
// }

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
    $vga = $_POST['vga'] ?? '';
    $penyimpanan = $_POST['penyimpanan'] ?? '';
    $sistemOperasi = $_POST['sistemOperasi'] ?? '';
    $kesesuaianPC = $_POST['kesesuaianPC'] ?? '';
    $kondisiKomputer = $_POST['kondisiKomputer'] ?? '';
    $detailKondisi = $_POST['detailKondisi'] ?? '';
    $historiPemeliharaan = $_POST['historiPemeliharaan'] ?? '';
    
    // Upload foto jika ada
    $fotoPath = '';
    if(isset($_FILES['fotoPerangkat']) && $_FILES['fotoPerangkat']['error'] == 0) {
        $uploadDir = 'uploads/images/';
        
        // Buat direktori jika belum ada
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . basename($_FILES['fotoPerangkat']['name']);
        $targetFilePath = $uploadDir . $fileName;
        
        // Upload file
        if(move_uploaded_file($_FILES['fotoPerangkat']['tmp_name'], $targetFilePath)) {
            $fotoPath = $targetFilePath;
        }
    }
    
    // Generate barcode path (jika mengimplementasikan penyimpanan barcode)
    $barcodePath = 'uploads/barcodes/' . $nomorAset . '.png';
    
    // Simpan data ke database (aktifkan saat sudah memiliki database)
    /*
    $sql = "INSERT INTO perangkat (nama_ruangan, nomor_aset, nomor_komputer, tahun_pengadaan, 
                                 nama_pengguna, penggunaan_sekarang, processor, ram, vga, 
                                 penyimpanan, sistem_operasi, kesesuaian_pc, kondisi_komputer, 
                                 detail_kondisi, histori_pemeliharaan, foto_path, barcode_path) 
            VALUES ('$namaRuangan', '$nomorAset', '$nomorKomputer', '$tahunPengadaan', 
                   '$namaPengguna', '$penggunaanSekarang', '$processor', '$ram', '$vga', 
                   '$penyimpanan', '$sistemOperasi', '$kesesuaianPC', '$kondisiKomputer', 
                   '$detailKondisi', '$historiPemeliharaan', '$fotoPath', '$barcodePath')";
    
    if (mysqli_query($conn, $sql)) {
        // Set success message
        $_SESSION['success_message'] = "Data perangkat berhasil ditambahkan";
        
        // Redirect ke halaman daftar perangkat
        header("Location: daftar-perangkat.php");
        exit();
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
    */
    
    // Simulasi sukses untuk demo
    $_SESSION['success_message'] = "Data perangkat berhasil ditambahkan";
    header("Location: daftar-perangkat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Perangkat | Sistem Informasi Komputer ESDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <?php include 'includes/navbar.php'; ?>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <h2 class="border-bottom pb-2">
                    <i class="bi bi-plus-circle-fill text-primary"></i> Tambah Data Perangkat Komputer
                </h2>
            </div>
        </div>

        <?php if(isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form id="formTambahPerangkat" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4 class="card-title mb-3">Data Identifikasi Perangkat</h4>
                            
                            <div class="mb-3">
                                <label for="namaRuangan" class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="namaRuangan" name="namaRuangan" required>
                                <div class="invalid-feedback">
                                    Nama ruangan wajib diisi
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="nomorAset" class="form-label">Nomor Aset <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomorAset" name="nomorAset" required>
                                <div class="invalid-feedback">
                                    Nomor aset wajib diisi
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="nomorKomputer" class="form-label">Nomor Komputer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomorKomputer" name="nomorKomputer" required>
                                <div class="invalid-feedback">
                                    Nomor komputer wajib diisi
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="tahunPengadaan" class="form-label">Tahun Pengadaan <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="tahunPengadaan" name="tahunPengadaan" min="2000" max="2050" required>
                                <div class="invalid-feedback">
                                    Tahun pengadaan wajib diisi dengan format yang benar
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="namaPengguna" class="form-label">Nama Pengguna Sekarang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="namaPengguna" name="namaPengguna" required>
                                <div class="invalid-feedback">
                                    Nama pengguna wajib diisi
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="penggunaanSekarang" class="form-label">Penggunaan Sekarang</label>
                                <textarea class="form-control" id="penggunaanSekarang" name="penggunaanSekarang" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h4 class="card-title mb-3">Spesifikasi Perangkat</h4>
                            
                            <div class="mb-3">
                                <label for="processor" class="form-label">Processor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="processor" name="processor" placeholder="Contoh: Intel Core i5-10400 2.9GHz" required>
                                <div class="invalid-feedback">
                                    Spesifikasi processor wajib diisi
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="ram" class="form-label">RAM <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ram" name="ram" placeholder="Contoh: 8GB DDR4 2666MHz" required>
                                <div class="invalid-feedback">
                                    Spesifikasi RAM wajib diisi
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="vga" class="form-label">VGA</label>
                                <input type="text" class="form-control" id="vga" name="vga" placeholder="Contoh: NVIDIA GeForce GTX 1650 4GB">
                            </div>
                            
                            <div class="mb-3">
                                <label for="penyimpanan" class="form-label">Penyimpanan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="penyimpanan" name="penyimpanan" placeholder="Contoh: SSD 256GB + HDD 1TB" required>
                                <div class="invalid-feedback">
                                    Informasi penyimpanan wajib diisi
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="sistemOperasi" class="form-label">Sistem Operasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="sistemOperasi" name="sistemOperasi" placeholder="Contoh: Windows 10 Pro 64-bit" required>
                                <div class="invalid-feedback">
                                    Sistem operasi wajib diisi
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="kesesuaianPC" class="form-label">Kesesuaian PC dalam Mendukung Pekerjaan</label>
                                <select class="form-select" id="kesesuaianPC" name="kesesuaianPC">
                                    <option value="sangat_sesuai">Sangat Sesuai</option>
                                    <option value="sesuai" selected>Sesuai</option>
                                    <option value="kurang_sesuai">Kurang Sesuai</option>
                                    <option value="tidak_sesuai">Tidak Sesuai</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="card-title mb-3">Kondisi dan Pemeliharaan</h4>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kondisiKomputer" class="form-label">Keterangan Kondisi Komputer Saat Ini <span class="text-danger">*</span></label>
                                <select class="form-select" id="kondisiKomputer" name="kondisiKomputer" required>
                                    <option value="" selected disabled>Pilih kondisi</option>
                                    <option value="sangat_baik">Sangat Baik</option>
                                    <option value="baik">Baik</option>
                                    <option value="cukup">Cukup</option>
                                    <option value="kurang">Kurang</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                                <div class="invalid-feedback">
                                    Kondisi komputer wajib dipilih
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="detailKondisi" class="form-label">Detail Kondisi</label>
                                <textarea class="form-control" id="detailKondisi" name="detailKondisi" rows="3" placeholder="Berikan detail kondisi perangkat saat ini..."></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="historiPemeliharaan" class="form-label">Histori Pemeliharaan</label>
                                <textarea class="form-control" id="historiPemeliharaan" name="historiPemeliharaan" rows="6" placeholder="Contoh: 12/05/2023 - Penggantian RAM dari 4GB ke 8GB"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="card-title mb-3">Media dan Identifikasi</h4>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fotoPerangkat" class="form-label">Foto Perangkat <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" id="fotoPerangkat" name="fotoPerangkat" accept="image/*" required>
                                <div class="invalid-feedback">
                                    Foto perangkat wajib diunggah
                                </div>
                                <div class="form-text">
                                    Unggah foto tampak depan perangkat. Maksimal ukuran 5MB.
                                </div>
                            </div>
                            <div class="mt-3 mb-3">
                                <div class="preview-container" id="previewContainer">
                                    <img id="previewImage" class="img-fluid d-none" alt="Preview Foto">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Barcode</label>
                                <div class="card p-3 text-center">
                                    <div id="barcodeContainer" class="mb-2">
                                        <img src="https://via.placeholder.com/200x100.png?text=Barcode" alt="Barcode" class="img-fluid">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="generateBarcode">
                                        <i class="bi bi-upc-scan"></i> Generate Barcode
                                    </button>
                                    <div class="form-text">
                                        Barcode akan dibuat otomatis berdasarkan nomor aset.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <hr>
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-save"></i> Simpan Data Perangkat
                            </button>
                            <a href="daftar-perangkat.php" class="btn btn-outline-secondary btn-lg ms-2">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
