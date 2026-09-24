<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class VehicleCheckout extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'vehicle_checkouts';

    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'petugas_id',
        'odometer_keluar',
        'level_bbm_keluar',
        'kondisi_kendaraan',
        'checklist_kelengkapan',
        'foto_kondisi',
        'catatan',
        'waktu_keluar',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'odometer_keluar' => 'integer',
            'checklist_kelengkapan' => 'array',
            'foto_kondisi' => 'array',
            'waktu_keluar' => 'datetime',
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
     * Relasi ke petugas garasi yang menyerahkan armada.
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('handover')
            ->setDescriptionForEvent(fn (string $eventName) => "Checkout armada keluar penugasan telah dicatat");
    }
}
