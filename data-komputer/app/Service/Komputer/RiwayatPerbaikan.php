<?php

namespace App\Service\Komputer;

use App\Models\RiwayatPerbaikanKomputer;
use Illuminate\Http\Request;

class RiwayatPerbaikan
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function validationStore(Request $request)
    {
        return $request->validate([
            'jenis_maintenance' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            'teknisi' => 'required|string|max:255',
            'komponen_diganti' => 'nullable|string|max:255',
            'biaya_maintenance' => 'nullable|numeric|min:0',
            'hasil_maintenance' => 'nullable|string|max:500',
            'rekomendasi' => 'nullable|string|max:500',
        ]);
    }

    public function validationUpdate(Request $request)
    {
        return $request->validate([
            'jenis_maintenance' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            'teknisi' => 'required|string|max:255',
            'komponen_diganti' => 'nullable|string|max:255',
            'biaya_maintenance' => 'nullable|numeric|min:0',
            'hasil_maintenance' => 'nullable|string|max:500',
            'rekomendasi' => 'nullable|string|max:500',
        ]);
    }

    public function store($data, $id_komputer)
    {

        $data['asset_id'] = $id_komputer;

        $riwayat = RiwayatPerbaikanKomputer::create($data);
        return $riwayat;
    }

    public function update($data, $id_riwayat)
    {
        $riwayat = RiwayatPerbaikanKomputer::findOrFail($id_riwayat);
        $riwayat->update($data);
        return $riwayat;
    }
}
