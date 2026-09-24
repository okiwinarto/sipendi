<?php

namespace App\Filament\Resources\VehicleMaintenances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehicleMaintenancesTable
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

                TextColumn::make('jenis_service')
                    ->label('Jenis Servis')
                    ->badge()
                    ->colors([
                        'primary' => 'rutin',
                        'info' => 'berkala',
                        'warning' => 'insidentil',
                        'danger' => 'perbaikan_kerusakan',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'rutin' => 'Rutin',
                        'berkala' => 'Berkala',
                        'insidentil' => 'Insidentil',
                        'perbaikan_kerusakan' => 'Perbaikan Kerusakan',
                        default => ucfirst($state),
                    }),

                TextColumn::make('tanggal_service')
                    ->label('Tanggal Servis')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('odometer_saat_service')
                    ->label('Odometer')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') . ' km')
                    ->sortable(),

                TextColumn::make('bengkel')
                    ->label('Bengkel')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('biaya')
                    ->label('Biaya')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('pencatat.name')
                    ->label('Dicatat Oleh')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal_service', 'desc')
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
