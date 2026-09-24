<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Garasi extends Model
{
    use HasFactory;

    protected $table = 'garasi';

    protected $fillable = [
        'nama_garasi',
        'alamat',
        'penanggung_jawab_id',
        'no_telp',
    ];

    /**
     * Kepala garasi / penanggung jawab pool.
     */
    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }

    /**
     * Armada kendaraan yang terparkir di garasi ini.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'garasi_id');
    }

    /**
     * Sopir yang ditempatkan di garasi ini.
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'garasi_id');
    }
}
