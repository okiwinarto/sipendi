<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleCategory extends Model
{
    use HasFactory;

    protected $table = 'vehicle_categories';

    protected $fillable = [
        'nama_kategori',
        'keterangan',
    ];

    /**
     * Kendaraan dalam kategori ini.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'kategori_id');
    }
}
