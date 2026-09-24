<?php

namespace App\Filament\Resources\Drivers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DriversTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=Driver&background=0284c7&color=fff'),

                TextColumn::make('nama')
                    ->label('Nama Sopir')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('no_hp')
                    ->label('No. Kontak')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('garasi.nama_garasi')
                    ->label('Garasi Pool')
                    ->sortable(),

                TextColumn::make('jenis_sim')
                    ->label('SIM')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state, $record) => "SIM {$state} ({$record->no_sim})"),

                TextColumn::make('masa_berlaku_sim')
                    ->label('Masa Berlaku SIM')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'cuti' => 'warning',
                        'nonaktif' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'aktif' => '🟢 Siap Bertugas',
                        'cuti' => '🟠 Cuti / Izin',
                        'nonaktif' => '🔴 Nonaktif',
                        default => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'cuti' => 'Cuti',
                        'nonaktif' => 'Nonaktif',
                    ]),
                SelectFilter::make('jenis_sim')
                    ->options([
                        'A' => 'SIM A',
                        'B1' => 'SIM B1',
                        'B2' => 'SIM B2',
                    ]),
            ])
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
