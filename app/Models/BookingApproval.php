<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingApproval extends Model
{
    use HasFactory;

    protected $table = 'booking_approvals';

    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'approver_id',
        'role_approval',
        'tindakan',
        'catatan',
        'waktu_tindakan',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'waktu_tindakan' => 'datetime',
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
     * Relasi ke pengguna yang melakukan approval/rejection.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Scope untuk approval Kepala Garasi.
     */
    public function scopeGarasi(Builder $query): Builder
    {
        return $query->where('role_approval', 'kepala_garasi');
    }

    /**
     * Scope untuk approval Pimpinan.
     */
    public function scopePimpinan(Builder $query): Builder
    {
        return $query->where('role_approval', 'pimpinan');
    }

    /**
     * Scope tindakan disetujui.
     */
    public function scopeSetuju(Builder $query): Builder
    {
        return $query->where('tindakan', 'setuju');
    }

    /**
     * Scope tindakan ditolak.
     */
    public function scopeTolak(Builder $query): Builder
    {
        return $query->where('tindakan', 'tolak');
    }
}
