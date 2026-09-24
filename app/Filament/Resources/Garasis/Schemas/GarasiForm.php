<?php

namespace App\Filament\Resources\Garasis\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GarasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_garasi')
                    ->required(),
                Textarea::make('alamat')
                    ->columnSpanFull(),
                Select::make('penanggung_jawab_id')
                    ->relationship('penanggungJawab', 'name'),
                TextInput::make('no_telp')
                    ->tel(),
            ]);
    }
}
