<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Vehicle extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'vehicles';

    protected $fillable = [
        'garasi_id',
        'kategori_id',
        'no_polisi',
        'no_rangka',
        'no_mesin',
        'no_bpkb',
        'merk',
        'tipe_model',
        'tahun_pembuatan',
        'warna',
        'bahan_bakar',
        'kapasitas_penumpang',
        'foto_utama',
        'status',
        'odometer_terakhir',
        'interval_service_km',
        'interval_service_bulan',
        'tanggal_service_terakhir',
        'odometer_service_terakhir',
        'tanggal_pajak_tahunan',
        'tanggal_pajak_5tahunan',
        'tanggal_kir_berlaku',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tahun_pembuatan' => 'integer',
            'kapasitas_penumpang' => 'integer',
            'odometer_terakhir' => 'integer',
            'interval_service_km' => 'integer',
            'interval_service_bulan' => 'integer',
            'odometer_service_terakhir' => 'integer',
            'tanggal_service_terakhir' => 'date',
            'tanggal_pajak_tahunan' => 'date',
            'tanggal_pajak_5tahunan' => 'date',
            'tanggal_kir_berlaku' => 'date',
        ];
    }

    /**
     * Garasi asal pool kendaraan.
     */
    public function garasi(): BelongsTo
    {
        return $this->belongsTo(Garasi::class, 'garasi_id');
    }

    /**
     * Kategori kendaraan (Minibus, Pickup, dll).
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class, 'kategori_id');
    }

    /**
     * Nama lengkap kendaraan (misal: Toyota Avanza (E 1234 YX)).
     */
    public function getNamaLengkapAttribute(): string
    {
        return "{$this->merk} {$this->tipe_model} ({$this->no_polisi})";
    }

    /**
     * Riwayat servis dan pemeliharaan armada.
     */
    public function maintenances()
    {
        return $this->hasMany(VehicleMaintenance::class, 'vehicle_id');
    }

    /**
     * Riwayat pembayaran pajak dan uji KIR armada.
     */
    public function taxes()
    {
        return $this->hasMany(VehicleTax::class, 'vehicle_id');
    }

    /**
     * Riwayat peminjaman kendaraan ini.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'vehicle_id');
    }

    /**
     * Cek apakah armada sudah melewati jadwal servis rutin (KM atau Bulan).
     */
    public function isServiceDue(): bool
    {
        // 1. Cek batas interval kilometer
        if ($this->interval_service_km && $this->odometer_terakhir !== null) {
            $kmDiff = $this->odometer_terakhir - ($this->odometer_service_terakhir ?? 0);
            if ($kmDiff >= $this->interval_service_km) {
                return true;
            }
        }

        // 2. Cek batas interval bulan servis
        if ($this->interval_service_bulan && $this->tanggal_service_terakhir) {
            $dueDate = $this->tanggal_service_terakhir->copy()->addMonths($this->interval_service_bulan);
            if (now()->startOfDay()->gte($dueDate->startOfDay())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Cek apakah terdapat pajak atau KIR yang telah kedaluwarsa.
     */
    public function hasExpiredTax(): bool
    {
        $today = now()->startOfDay();

        if ($this->tanggal_pajak_tahunan && $this->tanggal_pajak_tahunan->startOfDay()->lt($today)) {
            return true;
        }

        if ($this->tanggal_pajak_5tahunan && $this->tanggal_pajak_5tahunan->startOfDay()->lt($today)) {
            return true;
        }

        if ($this->tanggal_kir_berlaku && $this->tanggal_kir_berlaku->startOfDay()->lt($today)) {
            return true;
        }

        return false;
    }

    /**
     * Konfigurasi jejak audit aktivitas (Spatie ActivityLog).
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('vehicle')
            ->setDescriptionForEvent(fn (string $eventName) => "Armada {$this->nama_lengkap} telah di-{$eventName}");
    }
}

