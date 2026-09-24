<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'grup',
    ];

    /**
     * Ambil nilai setting berdasarkan key.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Simpan nilai setting berdasarkan key.
     */
    public static function set(string $key, ?string $value, string $grup = 'umum'): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'grup' => $grup]
        );
    }
}
