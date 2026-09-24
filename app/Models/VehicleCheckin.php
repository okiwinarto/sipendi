<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class VehicleCheckin extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'vehicle_checkins';

    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'petugas_id',
        'odometer_masuk',
        'level_bbm_masuk',
        'kondisi_kendaraan',
        'ada_kerusakan',
        'deskripsi_kerusakan',
        'checklist_kelengkapan',
        'foto_kondisi',
        'rating_kondisi',
        'catatan',
        'waktu_masuk',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'odometer_masuk' => 'integer',
            'ada_kerusakan' => 'boolean',
            'checklist_kelengkapan' => 'array',
            'foto_kondisi' => 'array',
            'rating_kondisi' => 'integer',
            'waktu_masuk' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke peminjaman terkait.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Relasi ke petugas garasi yang menerima kembali armada.
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    /**
     * Hitung total kilometer perjalanan dinas (odometer masuk - odometer keluar).
     */
    public function getJarakTempuhAttribute(): int
    {
        $odoKeluar = $this->booking?->checkout?->odometer_keluar ?? 0;
        return max(0, $this->odometer_masuk - $odoKeluar);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('handover')
            ->setDescriptionForEvent(fn (string $eventName) => "Checkin armada kembali ke pool telah dicatat");
    }
}
