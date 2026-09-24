<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMaintenance extends Model
{
    use HasFactory;

    protected $table = 'vehicle_maintenances';

    protected $fillable = [
        'vehicle_id',
        'jenis_service',
        'tanggal_service',
        'odometer_saat_service',
        'bengkel',
        'biaya',
        'deskripsi_pekerjaan',
        'dokumen_nota',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_service' => 'date',
            'biaya' => 'decimal:2',
            'odometer_saat_service' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (VehicleMaintenance $maintenance) {
            $vehicle = $maintenance->vehicle;
            if ($vehicle) {
                // Perbarui data servis terakhir jika odometer servis ini lebih tinggi atau sama
                if ($maintenance->odometer_saat_service >= ($vehicle->odometer_service_terakhir ?? 0)) {
                    $vehicle->odometer_service_terakhir = $maintenance->odometer_saat_service;
                }

                if (! $vehicle->tanggal_service_terakhir || $maintenance->tanggal_service >= $vehicle->tanggal_service_terakhir) {
                    $vehicle->tanggal_service_terakhir = $maintenance->tanggal_service;
                }

                // Jika perbaikan kerusakan selesai dan armada berstatus maintenance / perlu perhatian, pulihkan ke tersedia
                if (in_array($vehicle->status, ['maintenance', 'perlu_perhatian']) && $maintenance->jenis_service === 'perbaikan_kerusakan') {
                    $vehicle->status = 'tersedia';
                }

                $vehicle->saveQuietly();
            }
        });
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
