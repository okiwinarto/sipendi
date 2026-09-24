<?php

namespace App\Filament\Resources\VehicleTaxes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VehicleTaxForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vehicle_id')
                    ->label('Armada Kendaraan')
                    ->relationship('vehicle', 'no_polisi')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->merk} {$record->tipe_model} ({$record->no_polisi})")
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('jenis_pajak')
                    ->label('Jenis Pajak / Uji Kelayakan')
                    ->options([
                        'tahunan' => 'Pajak Tahunan (PKB / STNK)',
                        'lima_tahunan' => 'Pajak 5 Tahunan & Ganti Plat Nomor',
                        'kir' => 'Uji Berkala KIR (Mobil Box / Pickup)',
                    ])
                    ->default('tahunan')
                    ->required(),

                DatePicker::make('tanggal_bayar')
                    ->label('Tanggal Pembayaran')
                    ->default(now()),

                DatePicker::make('masa_berlaku_sampai')
                    ->label('Masa Berlaku Sampai (Jatuh Tempo Baru)')
                    ->required(),

                TextInput::make('biaya')
                    ->label('Biaya Pembayaran (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->placeholder('0'),

                Select::make('status')
                    ->label('Status Masa Berlaku')
                    ->options([
                        'aktif' => 'Aktif (Berlaku)',
                        'akan_jatuh_tempo' => 'Akan Jatuh Tempo (H-30)',
                        'kadaluarsa' => 'Kedaluwarsa (Terlewat)',
                    ])
                    ->default('aktif')
                    ->required(),

                FileUpload::make('dokumen')
                    ->label('Foto / Scan Bukti Pembayaran Pajak (Notice Pajak / STNK / Buku KIR)')
                    ->directory('tax_documents')
                    ->maxSize(5120)
                    ->columnSpanFull(),

                Hidden::make('dicatat_oleh')
                    ->default(fn () => auth()->id()),
            ]);
    }
}
