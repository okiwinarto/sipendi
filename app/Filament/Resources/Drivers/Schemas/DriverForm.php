<?php

namespace App\Filament\Resources\Drivers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('garasi_id')
                    ->label('Garasi Pool Penugasan')
                    ->relationship('garasi', 'nama_garasi')
                    ->searchable()
                    ->preload(),

                Select::make('user_id')
                    ->label('Akun Pengguna Terkait (Opsional)')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                TextInput::make('nama')
                    ->label('Nama Lengkap Sopir')
                    ->required(),

                TextInput::make('no_hp')
                    ->label('Nomor WhatsApp / HP')
                    ->tel()
                    ->required(),

                TextInput::make('no_sim')
                    ->label('Nomor Surat Izin Mengemudi (SIM)')
                    ->required(),

                Select::make('jenis_sim')
                    ->label('Jenis Golongan SIM')
                    ->options([
                        'A' => 'SIM A (Mobil Penumpang/Pribadi)',
                        'B1' => 'SIM B1 (Mobil Penumpang & Bus/Truk Ringan)',
                        'B2' => 'SIM B2 (Kendaraan Alat Berat / Gandeng)',
                    ])
                    ->default('A')
                    ->required(),

                DatePicker::make('masa_berlaku_sim')
                    ->label('Masa Berlaku SIM')
                    ->displayFormat('d/m/Y')
                    ->required(),

                FileUpload::make('foto')
                    ->label('Pasfoto Sopir')
                    ->image()
                    ->directory('drivers')
                    ->avatar(),

                Select::make('status')
                    ->label('Status Penugasan')
                    ->options([
                        'aktif' => 'Aktif (Siap Bertugas)',
                        'cuti' => 'Cuti / Izin',
                        'nonaktif' => 'Nonaktif',
                    ])
                    ->default('aktif')
                    ->required(),
            ]);
    }
}
