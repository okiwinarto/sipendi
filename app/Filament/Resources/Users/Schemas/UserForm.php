<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap Pegawai')
                    ->required(),

                TextInput::make('nip')
                    ->label('NIP (Nomor Induk Pegawai)')
                    ->placeholder('Mis. 198801122015031001')
                    ->unique(ignoreRecord: true),

                TextInput::make('email')
                    ->label('Alamat Email (Akun Login)')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('password')
                    ->label('Kata Sandi (Password)')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->helperText('Kosongkan jika tidak ingin mengubah kata sandi.'),

                Select::make('roles')
                    ->label('Peran Pengguna (RBAC)')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->options([
                        'admin_it' => 'Admin IT (Superadmin)',
                        'kepala_garasi' => 'Kepala Garasi (Verifikator Teknis)',
                        'pimpinan' => 'Pimpinan (Penyetuju Akhir)',
                        'user_aplikasi' => 'User Aplikasi (Pemohon)',
                    ]),

                Select::make('unit_kerja_id')
                    ->label('Unit Kerja / Instalasi')
                    ->relationship('unitKerja', 'nama_unit')
                    ->searchable()
                    ->preload(),

                Select::make('garasi_id')
                    ->label('Garasi Pool (Khusus Staf Garasi)')
                    ->relationship('garasi', 'nama_garasi')
                    ->searchable()
                    ->preload(),

                TextInput::make('no_hp')
                    ->label('Nomor WhatsApp / HP')
                    ->tel(),

                FileUpload::make('foto')
                    ->label('Foto Profil')
                    ->image()
                    ->avatar()
                    ->directory('users'),

                Select::make('status')
                    ->label('Status Akun')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ])
                    ->default('aktif')
                    ->required(),
            ]);
    }
}
