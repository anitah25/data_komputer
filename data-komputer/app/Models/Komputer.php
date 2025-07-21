<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Komputer extends Model
{
    /** @use HasFactory<\Database\Factories\KomputerFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'kode_barang',
        'nama_komputer',
        'merek_komputer',
        'tahun_pengadaan',
        'spesifikasi_ram',
        'spesifikasi_vga',
        'spesifikasi_processor',
        'spesifikasi_penyimpanan',
        'sistem_operasi',
        'nama_pengguna_sekarang',
        'kesesuaian_pc',
        'kondisi_komputer',
        'keterangan_kondisi',
        'penggunaan_sekarang',
        'ruangan_id',
        'barcode',
    ];

    /**
     * Get the user that added this computer asset.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the galleries for this computer asset.
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(GalleryKomputer::class, 'asset_id');
    }

    /**
     * Get the maintenance history for this computer asset.
     */
    public function maintenanceHistories(): HasMany
    {
        return $this->hasMany(RiwayatPerbaikanKomputer::class, 'asset_id');
    }

    /**
     * Get the ruangan for this computer asset.
     */
    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }
}
