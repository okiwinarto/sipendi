<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_peminjaman')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('unit_kerja_id')
                    ->relationship('unitKerja', 'nama_unit')
                    ->required(),
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'plat_nomor'),
                Select::make('driver_id')
                    ->relationship('driver', 'nama_driver'),
                Select::make('jenis_pengemudi')
                    ->options(['sopir_dinas' => 'Sopir dinas', 'swakemudi' => 'Swakemudi'])
                    ->default('sopir_dinas')
                    ->required(),
                Textarea::make('tujuan_perjalanan')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('kota_tujuan')
                    ->required(),
                DatePicker::make('tanggal_berangkat')
                    ->minDate(today())
                    ->live()
                    ->required(),
                TimePicker::make('jam_berangkat')
                    ->required(),
                DatePicker::make('tanggal_kembali_rencana')
                    ->minDate(fn (Get $get) => $get('tanggal_berangkat') ? Carbon::parse($get('tanggal_berangkat')) : today())
                    ->required(),
                TimePicker::make('jam_kembali_rencana')
                    ->required(),
                TextInput::make('jumlah_penumpang')
                    ->required()
                    ->numeric()
                    ->default(1),
                Select::make('tingkat_prioritas')
                    ->options(['normal' => 'Normal', 'mendesak' => 'Mendesak'])
                    ->default('normal')
                    ->required(),
                TextInput::make('no_surat_tugas'),
                TextInput::make('file_surat_tugas'),
                Select::make('status')
                    ->options([
            'diajukan' => 'Diajukan',
            'diverifikasi_garasi' => 'Diverifikasi garasi',
            'ditolak_garasi' => 'Ditolak garasi',
            'disetujui' => 'Disetujui',
            'ditolak_pimpinan' => 'Ditolak pimpinan',
            'kendaraan_keluar' => 'Kendaraan keluar',
            'kendaraan_kembali' => 'Kendaraan kembali',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ])
                    ->default('diajukan')
                    ->required(),
                Textarea::make('catatan_pemohon')
                    ->columnSpanFull(),
                Textarea::make('alasan_penolakan')
                    ->columnSpanFull(),
            ]);
    }
}
