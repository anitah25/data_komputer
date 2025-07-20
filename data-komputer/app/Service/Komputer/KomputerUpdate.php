<?php

namespace App\Service\Komputer;

use App\Models\Komputer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KomputerUpdate
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Validate the update input.
     */
    public function validateInput(Request $request, $id)
    {
        $validated = $request->validate([
            'nomor_aset' => [
                'required', 
                'string', 
                'max:50',
                Rule::unique('komputers')->ignore($id, 'id')
            ],
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
            'lokasi_penempatan' => 'required|string|max:100',
            'foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:gallery_komputers,id',
        ]);

        return $validated;
    }

    /**
     * Update the computer data.
     */
    public function updateKomputer(Komputer $komputer, array $data)
    {
        // Update the computer data
        $komputer->update($data);

        // Clear cache for this computer
        Cache::forget('komputer_' . $komputer->nomor_aset);
        Cache::forget('ruangan_list');

        return $komputer;
    }

    /**
     * Handle image updates for the computer.
     */
    public function handleGallery(Komputer $komputer, Request $request)
    {
        // Handle image deletions
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $gallery = $komputer->galleries()->find($imageId);
                if ($gallery) {
                    // Delete the image file from storage
                    if (Storage::disk('public')->exists($gallery->image_path)) {
                        Storage::disk('public')->delete($gallery->image_path);
                    }
                    // Delete the gallery record
                    $gallery->delete();
                }
            }
        }

        // Handle new images
        if ($request->hasFile('foto')) {
            $files = $request->file('foto');

            // Ensure files is always treated as an array
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('komputers', $fileName, 'public');

                // Save image data to gallery_komputers table
                $komputer->galleries()->create([
                    'image_path' => $path,
                    'image_type' => 'front',
                ]);
            }
        }
    }
}
