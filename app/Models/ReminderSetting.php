<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderSetting extends Model
{
    use HasFactory;

    protected $table = 'reminder_settings';

    protected $fillable = [
        'tipe_reminder',
        'h_minus_tahap1',
        'h_minus_tahap2',
        'h_minus_tahap3',
        'target_role',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'target_role' => 'array',
            'aktif' => 'boolean',
            'h_minus_tahap1' => 'integer',
            'h_minus_tahap2' => 'integer',
            'h_minus_tahap3' => 'integer',
        ];
    }

    /**
     * Dapatkan konfigurasi pengingat berdasarkan tipe.
     */
    public static function getSettingFor(string $type): ?self
    {
        return static::where('tipe_reminder', $type)->where('aktif', true)->first();
    }
}
