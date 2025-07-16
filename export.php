<?php
// Session handling dan koneksi database bisa ditambahkan di sini
session_start();
// Contoh koneksi database (aktifkan saat sudah memiliki database)
// $conn = mysqli_connect("localhost", "username", "password", "db_komputer_esdm");
// if (!$conn) {
//     die("Koneksi gagal: " . mysqli_connect_error());
// }

// Ambil tipe ekspor
$exportType = $_GET['type'] ?? 'excel';

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

// Fungsi untuk mendapatkan nama kondisi
function getKondisiName($kondisi) {
    switch($kondisi) {
        case 'sangat_baik': return 'Sangat Baik';
        case 'baik': return 'Baik';
        case 'cukup': return 'Cukup';
        case 'kurang': return 'Kurang';
        case 'rusak': return 'Rusak';
        default: return 'Tidak Diketahui';
    }
}

// Proses ekspor berdasarkan tipe
switch($exportType) {
    case 'excel':
        // Set header untuk download file Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="daftar_perangkat_'.date('Ymd').'.xls"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Output header tabel
        echo '<table border="1">';
        echo '<tr><th>No</th><th>Nomor Aset</th><th>Nomor Komputer</th><th>Ruangan</th><th>Pengguna</th><th>Processor</th><th>RAM</th><th>Penyimpanan</th><th>Kondisi</th></tr>';
        
        // Output data
        foreach($perangkat as $index => $item) {
            echo '<tr>';
            echo '<td>'.($index + 1).'</td>';
            echo '<td>'.$item['nomor_aset'].'</td>';
            echo '<td>'.$item['nomor_komputer'].'</td>';
            echo '<td>'.$item['nama_ruangan'].'</td>';
            echo '<td>'.$item['nama_pengguna'].'</td>';
            echo '<td>'.$item['processor'].'</td>';
            echo '<td>'.$item['ram'].'</td>';
            echo '<td>'.$item['penyimpanan'].'</td>';
            echo '<td>'.getKondisiName($item['kondisi_komputer']).'</td>';
            echo '</tr>';
        }
        
        echo '</table>';
        break;
        
    case 'csv':
        // Set header untuk download file CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="daftar_perangkat_'.date('Ymd').'.csv"');
        
        // Buat file pointer untuk output
        $output = fopen('php://output', 'w');
        
        // Output header CSV
        fputcsv($output, ['No', 'Nomor Aset', 'Nomor Komputer', 'Ruangan', 'Pengguna', 'Processor', 'RAM', 'Penyimpanan', 'Kondisi']);
        
        // Output data
        foreach($perangkat as $index => $item) {
            fputcsv($output, [
                $index + 1,
                $item['nomor_aset'],
                $item['nomor_komputer'],
                $item['nama_ruangan'],
                $item['nama_pengguna'],
                $item['processor'],
                $item['ram'],
                $item['penyimpanan'],
                getKondisiName($item['kondisi_komputer'])
            ]);
        }
        fclose($output);
        break;
        
    case 'pdf':
        // Catatan: Untuk PDF, perlu library seperti FPDF/TCPDF
        // Berikut adalah contoh sederhana simulasi download PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="daftar_perangkat_'.date('Ymd').'.pdf"');
        echo "Ini adalah simulasi export PDF. Di implementasi sebenarnya, gunakan library seperti FPDF atau TCPDF.";
        break;
        
    default:
        echo "Tipe export tidak valid";
        break;
}
exit();
?>
