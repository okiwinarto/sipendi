<?php

namespace App\Filament\Resources\VehicleMaintenances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VehicleMaintenanceForm
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

                Select::make('jenis_service')
                    ->label('Jenis Servis / Pemeliharaan')
                    ->options([
                        'rutin' => 'Servis Rutin (Ganti Oli, Filter, Pengecekan Ringan)',
                        'berkala' => 'Servis Berkala (Tune-up, Rem, Ban)',
                        'insidentil' => 'Perbaikan Insidentil (AC, Aki, Kelistrikan)',
                        'perbaikan_kerusakan' => 'Perbaikan Kerusakan Pasca-Dinas',
                    ])
                    ->default('berkala')
                    ->required(),

                DatePicker::make('tanggal_service')
                    ->label('Tanggal Pelaksanaan Servis')
                    ->default(now())
                    ->required(),

                TextInput::make('odometer_saat_service')
                    ->label('Odometer Saat Servis (KM)')
                    ->required()
                    ->numeric()
                    ->suffix('KM'),

                TextInput::make('bengkel')
                    ->label('Nama Bengkel Rekanan')
                    ->placeholder('Mis. Auto2000 Cirebon / Bengkel Pool Internal RSUD')
                    ->maxLength(100),

                TextInput::make('biaya')
                    ->label('Total Biaya Servis (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->placeholder('0'),

                Textarea::make('deskripsi_pekerjaan')
                    ->label('Rincian Pekerjaan / Pergantian Suku Cadang')
                    ->placeholder('Rincikan suku cadang yang diganti, perbaikan yang dilakukan...')
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('dokumen_nota')
                    ->label('Bukti Kwitansi / Faktur / Nota Servis')
                    ->directory('maintenance_receipts')
                    ->maxSize(5120)
                    ->columnSpanFull(),

                Hidden::make('dicatat_oleh')
                    ->default(fn () => auth()->id()),
            ]);
    }
}
