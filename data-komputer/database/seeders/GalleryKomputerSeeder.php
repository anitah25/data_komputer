<?php

namespace Database\Seeders;

use App\Models\GalleryKomputer;
use App\Models\Komputer;
use Illuminate\Database\Seeder;

class GalleryKomputerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan semua aset komputer
        $komputers = Komputer::all();

        // Daftar tipe gambar
        $imageTypes = ['front', 'back', 'side', 'detail', 'keyboard', 'screen', 'ports', 'inside'];

        // Untuk setiap aset komputer, tambahkan 1-4 gambar
        foreach ($komputers as $komputer) {
            $numImages = rand(1, 4);

            for ($i = 0; $i < $numImages; $i++) {
                // Pilih tipe gambar acak
                $imageType = $imageTypes[array_rand($imageTypes)];

                // Gunakan path gambar yang tetap
                $imagePath = 'komputers/msi_1_ports_rxysAvI5.jpg';

                GalleryKomputer::create([
                    'asset_id' => $komputer->id,
                    'image_path' => $imagePath,
                    'image_type' => $imageType,
                ]);
            }
        }
    }
}
