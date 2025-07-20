<?php

namespace Database\Seeders;

use App\Models\Komputer;
use App\Models\User;
use Illuminate\Database\Seeder;

class KomputerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan semua user untuk dijadikan admin yang menambahkan aset
        $users = User::all();

        // Daftar lokasi penempatan yang mungkin
        $locations = [
            'Kantor Pusat Lt.1',
            'Kantor Pusat Lt.2',
            'Kantor Pusat Lt.3',
            'Laboratorium',
            'Kantor Cabang Solo',
            'Kantor Cabang Serayu Tengah',
            'Kantor Cabang Merapi',
            'Kantor Cabang Kendeng Selatan',
            'Kantor Cabang Sewu Lawu',
            'Kantor Cabang Slamet Selatan',
            'Kantor Cabang Kendeng Muria',
            'Kantor Cabang Slamet Utara',
            'Kantor Cabang Ungaran Telomoyo',
            'Kantor Cabang Serayu Selatan',
            'Kantor Cabang Serayu Utara',
        ];

        // Daftar merek komputer
        $brands = ['Lenovo', 'HP', 'Dell', 'Asus', 'Acer', 'MSI', 'Apple', 'Samsung', 'Toshiba'];

        // Daftar spesifikasi RAM
        $ramSpecs = ['DDR3 4GB', 'DDR3 8GB', 'DDR4 8GB', 'DDR4 16GB', 'DDR5 16GB', 'DDR5 32GB'];

        // Daftar spesifikasi VGA
        $vgaSpecs = [
            'Intel UHD Graphics',
            'Intel Iris Xe Graphics',
            'NVIDIA GeForce GTX 1650',
            'NVIDIA GeForce GTX 1660',
            'NVIDIA GeForce RTX 3050',
            'NVIDIA GeForce RTX 3060',
            'AMD Radeon Vega 8',
            'AMD Radeon RX 560',
            'AMD Radeon RX 6500',
        ];

        // Daftar spesifikasi processor
        $processors = [
            'Intel Core i3 10th Gen',
            'Intel Core i5 10th Gen',
            'Intel Core i7 10th Gen',
            'Intel Core i5 11th Gen',
            'Intel Core i7 11th Gen',
            'Intel Core i9 11th Gen',
            'AMD Ryzen 3 5000 Series',
            'AMD Ryzen 5 5000 Series',
            'AMD Ryzen 7 5000 Series',
            'AMD Ryzen 9 5000 Series',
        ];

        // Daftar spesifikasi penyimpanan
        $storageSpecs = [
            'SSD 256GB',
            'SSD 512GB',
            'SSD 1TB',
            'HDD 500GB',
            'HDD 1TB',
            'HDD 2TB',
            'SSD 256GB + HDD 1TB',
            'SSD 512GB + HDD 1TB',
        ];

        // Daftar sistem operasi
        $osList = [
            'Windows 10 Pro',
            'Windows 10 Home',
            'Windows 11 Pro',
            'Windows 11 Home',
            'Ubuntu 22.04',
            'Debian 11',
            'macOS Monterey',
            'macOS Ventura',
        ];

        // Daftar kesesuaian PC
        $suitability = ['Sesuai', 'Kurang Sesuai', 'Tidak Sesuai', 'Perlu Upgrade'];

        // Daftar kondisi untuk keterangan kondisi
        $conditions = [
            'Baik dan berfungsi normal',
            'Baik, dengan beberapa masalah minor',
            'Butuh perbaikan di beberapa komponen',
            'Perlu penggantian komponen segera',
            'Layar bermasalah, perlu penggantian',
            'Baterai rusak, perlu penggantian',
            'Keyboard tidak berfungsi dengan baik',
            'Overheating pada penggunaan berat',
        ];
        
        // Daftar kondisi komputer sesuai form tambah
        $kondisiKomputer = [
            'Sangat Baik',
            'Baik',
            'Cukup',
            'Kurang',
            'Rusak',
        ];
        
        // Daftar nama file barcode yang tersedia
        $barcodeFiles = ['ausdas.png', 'fasaf.png'];

        // Buat 50 data random untuk aset komputer
        for ($i = 0; $i < 50; $i++) {
            $assetNumber = 'ESDM-PC-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $year = rand(2018, 2025);
            $brand = $brands[array_rand($brands)];
            $computerName = $brand . '-' . strtoupper(substr(md5(microtime()), 0, 6));

            // Pilih user acak sebagai admin yang menambahkan
            $adminUser = $users->random();

            // Pilih user acak sebagai pengguna saat ini
            $currentUser = $users->random()->nama_lengkap;
            
            // Pilih file barcode acak
            $barcodeFile = $barcodeFiles[array_rand($barcodeFiles)];
            $barcodePath = 'barcode/' . $barcodeFile;

            Komputer::create([
                'user_id' => $adminUser->id,
                'nomor_aset' => $assetNumber,
                'nama_komputer' => $computerName,
                'merek_komputer' => $brand,
                'tahun_pengadaan' => $year,
                'spesifikasi_ram' => $ramSpecs[array_rand($ramSpecs)],
                'spesifikasi_vga' => $vgaSpecs[array_rand($vgaSpecs)],
                'spesifikasi_processor' => $processors[array_rand($processors)],
                'spesifikasi_penyimpanan' => $storageSpecs[array_rand($storageSpecs)],
                'sistem_operasi' => $osList[array_rand($osList)],
                'nama_pengguna_sekarang' => $currentUser,
                'kesesuaian_pc' => $suitability[array_rand($suitability)],
                'kondisi_komputer' => $kondisiKomputer[array_rand($kondisiKomputer)],
                'keterangan_kondisi' => $conditions[array_rand($conditions)],
                'lokasi_penempatan' => $locations[array_rand($locations)],
                'barcode' => $barcodePath,
            ]);
        }
    }
}
