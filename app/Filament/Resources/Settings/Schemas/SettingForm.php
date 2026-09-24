<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Kunci Pengaturan (Key)')
                    ->required()
                    ->disabled(fn ($record) => $record !== null)
                    ->helperText('Identifier unik untuk pengaturan ini.'),

                Select::make('grup')
                    ->label('Kategori Grup')
                    ->options([
                        'umum' => 'Pengaturan Umum',
                        'pejabat' => 'Pejabat & Penandatangan',
                    ])
                    ->default('umum')
                    ->required(),

                Textarea::make('value')
                    ->label('Nilai Pengaturan (Value)')
                    ->rows(3)
                    ->columnSpanFull()
                    ->helperText('Isi nilai pengaturan, misal nama direktur, NIP, alamat instansi, dll.'),
            ]);
    }
}
