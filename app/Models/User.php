<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'email', 'password', 'unit_kerja_id', 'garasi_id', 'nip', 'no_hp', 'foto', 'status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * Tentukan apakah pengguna berhak mengakses panel Filament tertentu.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya Admin IT dan Kepala Garasi yang berhak mengakses panel backoffice Filament
        return $this->hasAnyRole(['admin_it', 'kepala_garasi']);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin_it');
    }

    public function isKepalaGarasi(): bool
    {
        return $this->hasAnyRole(['kepala_garasi', 'admin_it']);
    }

    public function isPimpinan(): bool
    {
        return $this->hasAnyRole(['pimpinan', 'admin_it']);
    }

    /**
     * Relasi ke Unit Kerja pegawai.
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    /**
     * Relasi ke Garasi (khususnya untuk Kepala Garasi / staf pool).
     */
    public function garasi(): BelongsTo
    {
        return $this->belongsTo(Garasi::class, 'garasi_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Konfigurasi jejak audit aktivitas (Spatie ActivityLog).
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'unit_kerja_id', 'garasi_id', 'nip', 'no_hp', 'status'])
            ->logOnlyDirty()
            ->useLogName('user')
            ->setDescriptionForEvent(fn (string $eventName) => "Data pengguna {$this->name} telah di-{$eventName}");
    }
}
