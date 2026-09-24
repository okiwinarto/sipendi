<?php

namespace App\Filament\Resources\VehicleTaxes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehicleTaxesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.no_polisi')
                    ->label('Armada')
                    ->description(fn ($record) => "{$record->vehicle?->merk} {$record->vehicle?->tipe_model}")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_pajak')
                    ->label('Jenis Pajak')
                    ->badge()
                    ->colors([
                        'primary' => 'tahunan',
                        'warning' => 'lima_tahunan',
                        'info' => 'kir',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'tahunan' => 'Tahunan (STNK)',
                        'lima_tahunan' => '5 Tahunan (Plat)',
                        'kir' => 'Uji KIR',
                        default => ucfirst($state),
                    }),

                TextColumn::make('tanggal_bayar')
                    ->label('Tgl Bayar')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('masa_berlaku_sampai')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('biaya')
                    ->label('Biaya')
                    ->money('IDR')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'aktif',
                        'warning' => 'akan_jatuh_tempo',
                        'danger' => 'kadaluarsa',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'aktif' => 'Aktif',
                        'akan_jatuh_tempo' => 'Akan Jatuh Tempo',
                        'kadaluarsa' => 'Kedaluwarsa',
                        default => ucfirst($state),
                    }),

                TextColumn::make('pencatat.name')
                    ->label('Dicatat Oleh')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('masa_berlaku_sampai', 'asc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
