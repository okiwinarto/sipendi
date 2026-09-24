<?php

namespace App\Filament\Resources\ReminderSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReminderSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipe_reminder')
                    ->label('Tipe Pengingat')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'service' => 'Servis Berkala',
                        'pajak_tahunan' => 'Pajak Tahunan',
                        'pajak_5tahunan' => 'Pajak 5 Tahunan',
                        'kir' => 'Uji KIR',
                        'sim_sopir' => 'SIM Sopir',
                        default => ucfirst($state),
                    }),

                TextColumn::make('h_minus_tahap1')
                    ->label('Tahap 1')
                    ->formatStateUsing(fn ($state) => "H-{$state}")
                    ->alignCenter(),

                TextColumn::make('h_minus_tahap2')
                    ->label('Tahap 2')
                    ->formatStateUsing(fn ($state) => "H-{$state}")
                    ->alignCenter(),

                TextColumn::make('h_minus_tahap3')
                    ->label('Tahap 3')
                    ->formatStateUsing(fn ($state) => "H-{$state}")
                    ->alignCenter(),

                TextColumn::make('target_role')
                    ->label('Target Notifikasi')
                    ->badge()
                    ->separator(','),

                IconColumn::make('aktif')
                    ->label('Status')
                    ->boolean(),
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
