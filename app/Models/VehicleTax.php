<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleTax extends Model
{
    use HasFactory;

    protected $table = 'vehicle_taxes';

    protected $fillable = [
        'vehicle_id',
        'jenis_pajak',
        'tanggal_bayar',
        'masa_berlaku_sampai',
        'biaya',
        'dokumen',
        'status',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
            'masa_berlaku_sampai' => 'date',
            'biaya' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (VehicleTax $tax) {
            // Otomatis tentukan status berdasarkan masa berlaku
            if ($tax->masa_berlaku_sampai) {
                $today = Carbon::today();
                $diffDays = $today->diffInDays($tax->masa_berlaku_sampai, false);

                if ($diffDays < 0) {
                    $tax->status = 'kadaluarsa';
                } elseif ($diffDays <= 30) {
                    $tax->status = 'akan_jatuh_tempo';
                } else {
                    $tax->status = 'aktif';
                }
            }
        });

        static::saved(function (VehicleTax $tax) {
            $vehicle = $tax->vehicle;
            if ($vehicle && $tax->masa_berlaku_sampai) {
                // Perbarui tanggal pajak di armada kendaraan
                if ($tax->jenis_pajak === 'tahunan') {
                    $vehicle->tanggal_pajak_tahunan = $tax->masa_berlaku_sampai;
                } elseif ($tax->jenis_pajak === 'lima_tahunan') {
                    $vehicle->tanggal_pajak_5tahunan = $tax->masa_berlaku_sampai;
                } elseif ($tax->jenis_pajak === 'kir') {
                    $vehicle->tanggal_kir_berlaku = $tax->masa_berlaku_sampai;
                }

                // Jika status armada 'perlu_perhatian' dan pajak sudah diperbarui/berlaku, pulihkan status ke tersedia
                if ($vehicle->status === 'perlu_perhatian' && ! $vehicle->hasExpiredTax()) {
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
