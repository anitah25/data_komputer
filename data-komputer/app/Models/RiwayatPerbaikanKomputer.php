<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPerbaikanKomputer extends Model
{
    /** @use HasFactory<\Database\Factories\RiwayatPerbaikanKomputerFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asset_id',
        'jenis_maintenance',
        'keterangan',
        'teknisi',
        'komponen_diganti',
        'biaya_maintenance',
        'hasil_maintenance',
        'rekomendasi',
    ];

    /**
     * Get the computer asset that this maintenance history belongs to.
     */
    public function komputer(): BelongsTo
    {
        return $this->belongsTo(Komputer::class, 'asset_id');
    }
}
