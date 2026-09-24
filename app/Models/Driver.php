<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';

    protected $fillable = [
        'garasi_id',
        'user_id',
        'nama',
        'no_hp',
        'no_sim',
        'jenis_sim',
        'masa_berlaku_sim',
        'foto',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'masa_berlaku_sim' => 'date',
        ];
    }

    /**
     * Garasi penugasan sopir.
     */
    public function garasi(): BelongsTo
    {
        return $this->belongsTo(Garasi::class, 'garasi_id');
    }

    /**
     * Akun user terkait sopir (jika memiliki akses login).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Cek apakah masa berlaku SIM telah habis.
     */
    public function isSimExpired(): bool
    {
        return $this->masa_berlaku_sim && $this->masa_berlaku_sim->startOfDay()->lt(now()->startOfDay());
    }

    /**
     * Cek apakah masa berlaku SIM akan segera habis dalam kurun hari tertentu.
     */
    public function isSimExpiring(int $days = 30): bool
    {
        if (! $this->masa_berlaku_sim) {
            return false;
        }

        $today = now()->startOfDay();
        $simDate = $this->masa_berlaku_sim->startOfDay();

        return $simDate->gte($today) && $today->diffInDays($simDate, false) <= $days;
    }
}

