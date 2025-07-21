<?php

namespace App\Service\Komputer;

use App\Models\Komputer;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class KomputerStore
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function validateInput(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:komputers,kode_barang',
            'nama_komputer' => 'required|string|max:100',
            'merek_komputer' => 'required|string|max:50',
            'tahun_pengadaan' => 'required|integer|min:2000|max:' . date('Y'),
            'spesifikasi_ram' => 'required|string|max:50',
            'spesifikasi_vga' => 'nullable|string|max:50',
            'spesifikasi_processor' => 'required|string|max:50',
            'spesifikasi_penyimpanan' => 'required|string|max:50',
            'sistem_operasi' => 'required|string|max:50',
            'nama_pengguna_sekarang' => 'nullable|string|max:100',
            'kesesuaian_pc' => 'required|string|max:50',
            'kondisi_komputer' => 'required|string|max:50',
            'keterangan_kondisi' => 'required|string|max:255',
            'penggunaan_sekarang' => 'required|string|max:100',
            'ruangan_id' => 'required|exists:ruangans,id',
            'barcode' => 'nullable|string|max:100|unique:komputers,barcode',
            'foto' => 'required|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10048', // Maksimal 10MB per foto
        ]);

        return $validated;
    }

    public function generateQRCode($kode_barang)
    {
        // Path yang akan digunakan untuk menyimpan QR code
        $directory = 'barcode';
        $filename = "{$kode_barang}.png";
        $path = "{$directory}/{$filename}";

        // Konfigurasi QR code
        $barcodeOptions = new QROptions([
            'outputType' => QROutputInterface::GDIMAGE_PNG,
            'eccLevel' => EccLevel::L,
            'scale' => 10,
            'imageBase64' => false,
            'moduleValues' => [
                // Warna untuk blok QR
                1536 => [0, 0, 0], // marker dark
                6    => [0, 0, 0], // dark
                // Warna untuk background QR
                5632 => [255, 255, 255], // marker light
                7    => [255, 255, 255], // light
            ],
        ]);

        // Generate QR code dengan URL scan yang lengkap
        $baseUrl = '/scan'; // Menggunakan helper url() Laravel
        $fullUrl = "{$baseUrl}/{$kode_barang}";

        // Buat objek QR code
        $qrCode = new QRCode($barcodeOptions);

        // Generate QR code sebagai string
        $qrCodeImage = $qrCode->render($fullUrl);

        // Simpan file menggunakan Storage
        Storage::put($path, $qrCodeImage);

        // Return path publik yang bisa diakses browser
        return $path;
    }

    public function storeKomputer($datas)
    {
        // Simpan data komputer ke database
        $komputer = Komputer::create([
            'user_id' => auth()->id(), // Menggunakan ID user yang sedang login
            ...$datas
        ]);

        return $komputer;
    }

    public function storeGallery(Komputer $komputer, Request $request)
    {
        if ($request->hasFile('foto')) {
            $files = $request->file('foto');

            // Ensure $files is always treated as an array
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('komputers', $fileName, 'public');

                // Simpan data foto ke tabel gallery_komputers
                $komputer->galleries()->create([
                    'image_path' => $path,
                    'image_type' => 'front',
                ]);
            }
        }
    }
}
