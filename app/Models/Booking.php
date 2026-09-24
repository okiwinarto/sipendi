<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Booking extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'bookings';

    protected $fillable = [
        'kode_peminjaman',
        'user_id',
        'unit_kerja_id',
        'vehicle_id',
        'driver_id',
        'jenis_pengemudi',
        'tujuan_perjalanan',
        'kota_tujuan',
        'tanggal_berangkat',
        'jam_berangkat',
        'tanggal_kembali_rencana',
        'jam_kembali_rencana',
        'jumlah_penumpang',
        'tingkat_prioritas',
        'no_surat_tugas',
        'file_surat_tugas',
        'status',
        'catatan_pemohon',
        'alasan_penolakan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_berangkat' => 'date',
            'tanggal_kembali_rencana' => 'date',
            'jumlah_penumpang' => 'integer',
        ];
    }

    /**
     * Relasi ke pemohon (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke unit kerja pemohon.
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    /**
     * Relasi ke armada kendaraan yang ditetapkan / diajukan.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    /**
     * Relasi ke sopir dinas yang ditugaskan.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * Relasi ke seluruh riwayat persetujuan peminjaman.
     */
    public function approvals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BookingApproval::class, 'booking_id')->orderBy('waktu_tindakan', 'asc');
    }

    /**
     * Persetujuan verifikasi teknis oleh Kepala Garasi.
     */
    public function garasiApproval(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BookingApproval::class, 'booking_id')
            ->where('role_approval', 'kepala_garasi')
            ->latestOfMany('waktu_tindakan');
    }

    /**
     * Persetujuan akhir oleh Pimpinan.
     */
    public function pimpinanApproval(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BookingApproval::class, 'booking_id')
            ->where('role_approval', 'pimpinan')
            ->latestOfMany('waktu_tindakan');
    }

    /**
     * Cek apakah peminjaman siap diverifikasi oleh Kepala Garasi.
     */
    public function canBeVerifiedByGarasi(): bool
    {
        return $this->status === 'diajukan';
    }

    /**
     * Cek apakah peminjaman siap disetujui oleh Pimpinan.
     */
    public function canBeApprovedByPimpinan(): bool
    {
        return $this->status === 'diverifikasi_garasi';
    }

    /**
     * Relasi ke serah terima keluar (Checkout).
     */
    public function checkout(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(VehicleCheckout::class, 'booking_id');
    }

    /**
     * Relasi ke serah terima masuk (Checkin).
     */
    public function checkin(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(VehicleCheckin::class, 'booking_id');
    }

    /**
     * Cek apakah peminjaman siap serah terima keluar (Checkout).
     */
    public function canBeCheckedOut(): bool
    {
        return $this->status === 'disetujui';
    }

    /**
     * Cek apakah peminjaman siap serah terima masuk (Checkin).
     */
    public function canBeCheckedIn(): bool
    {
        return $this->status === 'kendaraan_keluar';
    }

    /**
     * Generate format nomor registrasi resmi SIPENDI:
     * SPD/{KODE_UNIT}/{TAHUN}{BULAN}/{URUT_4DIGIT}
     * Contoh: SPD/FARM/202609/0001
     */
    public static function generateKodePeminjaman(?UnitKerja $unit = null): string
    {
        $kodeUnit = $unit ? strtoupper($unit->kode_unit) : 'UMUM';
        $tahunBulan = Carbon::now()->format('Ym');

        $prefix = "SPD/{$kodeUnit}/{$tahunBulan}/";

        $lastBooking = static::where('kode_peminjaman', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $nextUrut = 1;
        if ($lastBooking) {
            $parts = explode('/', $lastBooking->kode_peminjaman);
            $lastNum = (int) end($parts);
            $nextUrut = $lastNum + 1;
        }

        $urutanPad = str_pad((string) $nextUrut, 4, '0', STR_PAD_LEFT);

        return "{$prefix}{$urutanPad}";
    }

    /**
     * Scope untuk memeriksa apakah ada jadwal yang bentrok (overlap) pada kendaraan tertentu.
     */
    public function scopeCheckOverlap(
        Builder $query,
        int $vehicleId,
        string $tglBerangkat,
        string $jamBerangkat,
        string $tglKembali,
        string $jamKembali,
        ?int $excludeId = null
    ): Builder {
        $start = "{$tglBerangkat} {$jamBerangkat}";
        $end = "{$tglKembali} {$jamKembali}";

        return $query->where('vehicle_id', $vehicleId)
            ->whereNotIn('status', ['ditolak_garasi', 'ditolak_pimpinan', 'dibatalkan', 'selesai'])
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($start, $end) {
                $q->whereRaw("CONCAT(tanggal_berangkat, ' ', jam_berangkat) < ?", [$end])
                  ->whereRaw("CONCAT(tanggal_kembali_rencana, ' ', jam_kembali_rencana) > ?", [$start]);
            });
    }

    /**
     * Label status dalam bahasa Indonesia dan badge warna.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'diajukan' => 'Diajukan',
            'diverifikasi_garasi' => 'Diverifikasi Garasi',
            'ditolak_garasi' => 'Ditolak Garasi',
            'disetujui' => 'Disetujui Pimpinan',
            'ditolak_pimpinan' => 'Ditolak Pimpinan',
            'kendaraan_keluar' => 'Sedang Berjalan',
            'kendaraan_kembali' => 'Kendaraan Kembali',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'diajukan' => 'warning',
            'diverifikasi_garasi' => 'info',
            'ditolak_garasi', 'ditolak_pimpinan', 'dibatalkan' => 'danger',
            'disetujui' => 'primary',
            'kendaraan_keluar' => 'info',
            'kendaraan_kembali', 'selesai' => 'success',
            default => 'gray',
        };
    }

    /**
     * Konfigurasi jejak audit aktivitas (Spatie ActivityLog).
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('booking')
            ->setDescriptionForEvent(fn (string $eventName) => "Permohonan {$this->kode_peminjaman} telah di-{$eventName}");
    }
}
