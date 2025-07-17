<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan UserSeeder terlebih dahulu karena ada relasi dengan tabel lainnya
        $this->call([
            UserSeeder::class,
            KomputerSeeder::class,
            GalleryKomputerSeeder::class,
            RiwayatPerbaikanKomputerSeeder::class,
        ]);
    }
}
