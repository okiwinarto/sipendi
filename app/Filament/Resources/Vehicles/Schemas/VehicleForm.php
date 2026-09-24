<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('garasi_id')
                    ->label('Garasi / Pool Kendaraan')
                    ->relationship('garasi', 'nama_garasi')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('kategori_id')
                    ->label('Kategori Kendaraan')
                    ->relationship('kategori', 'nama_kategori')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('no_polisi')
                    ->label('Nomor Polisi (Plat Nomor)')
                    ->placeholder('Mis. E 1234 YX')
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('merk')
                    ->label('Merk')
                    ->placeholder('Mis. Toyota')
                    ->required(),

                TextInput::make('tipe_model')
                    ->label('Tipe / Model')
                    ->placeholder('Mis. Avanza 1.3 G')
                    ->required(),

                TextInput::make('tahun_pembuatan')
                    ->label('Tahun Pembuatan')
                    ->numeric()
                    ->default(date('Y'))
                    ->required(),

                TextInput::make('warna')
                    ->label('Warna Kendaraan')
                    ->placeholder('Mis. Hitam Metalik')
                    ->required(),

                Select::make('bahan_bakar')
                    ->label('Jenis Bahan Bakar')
                    ->options([
                        'bensin' => 'Bensin',
                        'solar' => 'Solar',
                        'listrik' => 'Listrik',
                        'hybrid' => 'Hybrid',
                    ])
                    ->default('bensin')
                    ->required(),

                TextInput::make('kapasitas_penumpang')
                    ->label('Kapasitas Penumpang (Orang)')
                    ->numeric()
                    ->default(5)
                    ->required(),

                Select::make('status')
                    ->label('Status Ketersediaan')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'dipinjam' => 'Dipinjam',
                        'maintenance' => 'Dalam Perawatan (Maintenance)',
                        'perlu_perhatian' => 'Perlu Perhatian (Jatuh Tempo Pajak/Servis)',
                        'nonaktif' => 'Nonaktif / Tidak Dioperasikan',
                    ])
                    ->default('tersedia')
                    ->required(),

                TextInput::make('odometer_terakhir')
                    ->label('Odometer Terakhir (KM)')
                    ->numeric()
                    ->default(0)
                    ->suffix('km')
                    ->required(),

                TextInput::make('interval_service_km')
                    ->label('Interval Servis (KM)')
                    ->numeric()
                    ->default(5000)
                    ->suffix('km'),

                TextInput::make('interval_service_bulan')
                    ->label('Interval Servis (Bulan)')
                    ->numeric()
                    ->default(6)
                    ->suffix('bulan'),

                DatePicker::make('tanggal_service_terakhir')
                    ->label('Tanggal Servis Terakhir')
                    ->displayFormat('d/m/Y'),

                TextInput::make('odometer_service_terakhir')
                    ->label('Odometer Saat Servis Terakhir')
                    ->numeric()
                    ->suffix('km'),

                DatePicker::make('tanggal_pajak_tahunan')
                    ->label('Masa Berlaku Pajak Tahunan (STNK)')
                    ->displayFormat('d/m/Y'),

                DatePicker::make('tanggal_pajak_5tahunan')
                    ->label('Masa Berlaku Plat 5 Tahunan')
                    ->displayFormat('d/m/Y'),

                DatePicker::make('tanggal_kir_berlaku')
                    ->label('Masa Berlaku Uji KIR (Jika Ada)')
                    ->displayFormat('d/m/Y'),

                TextInput::make('no_rangka')
                    ->label('Nomor Rangka'),

                TextInput::make('no_mesin')
                    ->label('Nomor Mesin'),

                TextInput::make('no_bpkb')
                    ->label('Nomor BPKB'),

                FileUpload::make('foto_utama')
                    ->label('Foto Fisik Kendaraan')
                    ->image()
                    ->directory('vehicles')
                    ->imageEditor(),

                Textarea::make('catatan')
                    ->label('Catatan Kondisi / Kelengkapan Tambahan')
                    ->columnSpanFull(),
            ]);
    }
}
