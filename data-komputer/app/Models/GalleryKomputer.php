<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryKomputer extends Model
{
    /** @use HasFactory<\Database\Factories\GalleryKomputerFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asset_id',
        'image_path',
        'image_type',
    ];

    /**
     * Get the computer asset that owns this gallery image.
     */
    public function komputer(): BelongsTo
    {
        return $this->belongsTo(Komputer::class, 'asset_id');
    }
}
