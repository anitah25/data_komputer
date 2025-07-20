<?php

namespace App\Service\Komputer;

use App\Models\Komputer;
use Illuminate\Support\Facades\Cache;

class KomputerGetData
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getFilteredKomputers(array $filters, int $perPage)
    {
        $query = Komputer::query();
        
        if (!empty($filters['keyword'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama_komputer', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhere('nomor_aset', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhere('merek_komputer', 'like', '%' . $filters['keyword'] . '%');
            });
        }
        
        if (!empty($filters['kondisi'])) {
            $query->where('kondisi_komputer', $filters['kondisi']);
        }
        
        if (!empty($filters['ruangan'])) {
            $query->where('lokasi_penempatan', $filters['ruangan']);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
    }

    public function getUniqueRuangan()
    {
        return Cache::remember('ruangan_list', now()->addHours(24), function () {
            return Komputer::select('lokasi_penempatan')->distinct()->pluck('lokasi_penempatan');
        });
    }

    public function getByNomorAset(string $nomor_aset)
    {
        return Cache::remember('komputer_' . $nomor_aset, now()->addHours(24), function () use ($nomor_aset) {
            return Komputer::where('nomor_aset', $nomor_aset)->with('galleries')->firstOrFail();
        });
    }
}
