<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    use HasFactory;

    protected $table = 'unit_kerja';

    protected $fillable = [
        'nama_unit',
        'kode_unit',
        'keterangan',
    ];

    /**
     * Pegawai yang bernaung di unit kerja ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'unit_kerja_id');
    }

    /**
     * Permohonan peminjaman dari unit kerja ini.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'unit_kerja_id');
    }
}
