<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=User&background=0284c7&color=fff'),

                TextColumn::make('name')
                    ->label('Nama & NIP')
                    ->description(fn ($record) => $record->nip ? "NIP: {$record->nip}" : null)
                    ->weight('bold')
                    ->searchable(['name', 'nip'])
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email Login')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('roles.name')
                    ->label('Peran (Role)')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin_it' => 'danger',
                        'kepala_garasi' => 'warning',
                        'pimpinan' => 'info',
                        'user_aplikasi' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin_it' => 'Admin IT',
                        'kepala_garasi' => 'Kepala Garasi',
                        'pimpinan' => 'Pimpinan',
                        'user_aplikasi' => 'User Aplikasi',
                        default => $state,
                    }),

                TextColumn::make('unitKerja.nama_unit')
                    ->label('Unit Kerja')
                    ->badge()
                    ->color('slate')
                    ->sortable(),

                TextColumn::make('no_hp')
                    ->label('No. WhatsApp')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'nonaktif' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            ->filters([
                SelectFilter::make('unit_kerja_id')
                    ->label('Filter Unit Kerja')
                    ->relationship('unitKerja', 'nama_unit'),

                SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
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
