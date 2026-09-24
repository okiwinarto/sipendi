<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Actions\ViewAction;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu Aksi')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),

                TextColumn::make('log_name')
                    ->label('Modul')
                    ->badge()
                    ->colors([
                        'primary' => 'booking',
                        'success' => 'handover',
                        'warning' => 'vehicle',
                        'info' => 'default',
                    ]),

                TextColumn::make('description')
                    ->label('Aktivitas')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('causer.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->placeholder('Sistem Otomatis'),

                TextColumn::make('subject_type')
                    ->label('Objek Data')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->badge(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make()
                    ->infolist([
                        TextEntry::make('description')->label('Deskripsi'),
                        TextEntry::make('causer.name')->label('Dilakukan Oleh')->placeholder('Sistem Otomatis'),
                        TextEntry::make('created_at')->label('Waktu')->dateTime('d M Y, H:i:s'),
                        KeyValueEntry::make('properties.attributes')->label('Data Baru / Perubahan')->placeholder('Tidak ada perubahan nilai spesifik'),
                        KeyValueEntry::make('properties.old')->label('Data Lama')->placeholder('-'),
                    ]),
            ]);
    }
}
