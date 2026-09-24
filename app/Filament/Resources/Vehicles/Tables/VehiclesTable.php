<?php

namespace App\Filament\Resources\Vehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_utama')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=Car&background=0284c7&color=fff'),

                TextColumn::make('no_polisi')
                    ->label('No. Polisi')
                    ->weight('bold')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                TextColumn::make('merk')
                    ->label('Merk & Tipe')
                    ->formatStateUsing(fn ($record) => "{$record->merk} {$record->tipe_model}")
                    ->searchable(['merk', 'tipe_model'])
                    ->sortable(),

                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('garasi.nama_garasi')
                    ->label('Pool / Garasi')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'dipinjam' => 'info',
                        'maintenance' => 'warning',
                        'perlu_perhatian' => 'warning',
                        'nonaktif' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'tersedia' => '🟢 Tersedia',
                        'dipinjam' => '🔵 Dipinjam',
                        'maintenance' => '🟠 Maintenance',
                        'perlu_perhatian' => '🟠 Perlu Perhatian',
                        'nonaktif' => '🔴 Nonaktif',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('odometer_terakhir')
                    ->label('Odometer')
                    ->numeric()
                    ->suffix(' km')
                    ->sortable(),

                TextColumn::make('bahan_bakar')
                    ->label('BBM')
                    ->badge()
                    ->color('slate')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('tanggal_pajak_tahunan')
                    ->label('Pajak Tahunan')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('tanggal_kir_berlaku')
                    ->label('Masa KIR')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'dipinjam' => 'Dipinjam',
                        'maintenance' => 'Maintenance',
                        'perlu_perhatian' => 'Perlu Perhatian',
                        'nonaktif' => 'Nonaktif',
                    ]),

                SelectFilter::make('kategori_id')
                    ->label('Filter Kategori')
                    ->relationship('kategori', 'nama_kategori'),

                SelectFilter::make('bahan_bakar')
                    ->label('Filter BBM')
                    ->options([
                        'bensin' => 'Bensin',
                        'solar' => 'Solar',
                        'listrik' => 'Listrik',
                        'hybrid' => 'Hybrid',
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
