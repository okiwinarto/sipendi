<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Pengaturan')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'nama_instansi' => 'Nama Instansi',
                        'alamat_instansi' => 'Alamat Instansi',
                        'kontak_pool' => 'Kontak Pool Garasi',
                        'jam_layanan_pool' => 'Jam Layanan Pool',
                        'direktur_nama' => 'Nama Direktur',
                        'direktur_nip' => 'NIP Direktur',
                        'direktur_jabatan' => 'Jabatan Direktur',
                        'pejabat_pool_nama' => 'Nama Kepala Pool / Garasi',
                        'pejabat_pool_nip' => 'NIP Kepala Pool / Garasi',
                        'pejabat_pool_jabatan' => 'Jabatan Kepala Pool / Garasi',
                        'kota_penandatanganan' => 'Kota Penandatanganan',
                        default => ucwords(str_replace('_', ' ', $state))
                    })
                    ->description(fn ($record) => $record->key)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('value')
                    ->label('Nilai Pengaturan')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('grup')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pejabat' => 'indigo',
                        'umum' => 'sky',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('grup')
                    ->label('Filter Kategori')
                    ->options([
                        'umum' => 'Pengaturan Umum',
                        'pejabat' => 'Pejabat & Penandatangan',
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
