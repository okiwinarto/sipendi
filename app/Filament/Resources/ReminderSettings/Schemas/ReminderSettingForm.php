<?php

namespace App\Filament\Resources\ReminderSettings\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReminderSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tipe_reminder')
                    ->label('Kategori Pengingat')
                    ->options([
                        'service' => 'Jadwal Servis Berkala & Rutin',
                        'pajak_tahunan' => 'Pajak Tahunan STNK (PKB)',
                        'pajak_5tahunan' => 'Pajak 5 Tahunan & Ganti Plat Nomor',
                        'kir' => 'Uji Berkala KIR (Mobil Box / Pickup)',
                        'sim_sopir' => 'Masa Berlaku SIM Sopir Dinas',
                    ])
                    ->disabled(fn ($context) => $context === 'edit')
                    ->required(),

                TextInput::make('h_minus_tahap1')
                    ->label('Ambang Notifikasi Tahap 1 (Awal)')
                    ->numeric()
                    ->suffix('Hari sebelum')
                    ->default(30)
                    ->required(),

                TextInput::make('h_minus_tahap2')
                    ->label('Ambang Notifikasi Tahap 2 (Peringatan)')
                    ->numeric()
                    ->suffix('Hari sebelum')
                    ->default(14)
                    ->required(),

                TextInput::make('h_minus_tahap3')
                    ->label('Ambang Notifikasi Tahap 3 (Mendesak / H-1)')
                    ->numeric()
                    ->suffix('Hari sebelum')
                    ->default(1)
                    ->required(),

                CheckboxList::make('target_role')
                    ->label('Peran Penerima Notifikasi Pengingat')
                    ->options([
                        'admin_it' => 'Admin IT',
                        'kepala_garasi' => 'Kepala Garasi',
                        'pimpinan' => 'Pimpinan',
                    ])
                    ->required(),

                Toggle::make('aktif')
                    ->label('Status Pengingat Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}
