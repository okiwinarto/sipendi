<?php

namespace App\Filament\Resources\Garasis;

use App\Filament\Resources\Garasis\Pages\CreateGarasi;
use App\Filament\Resources\Garasis\Pages\EditGarasi;
use App\Filament\Resources\Garasis\Pages\ListGarasis;
use App\Filament\Resources\Garasis\Schemas\GarasiForm;
use App\Filament\Resources\Garasis\Tables\GarasisTable;
use App\Models\Garasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GarasiResource extends Resource
{
    protected static ?string $model = Garasi::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Manajemen Armada';

    protected static ?string $navigationLabel = 'Garasi & Pool';

    protected static ?string $modelLabel = 'Garasi / Pool';

    protected static ?string $pluralModelLabel = 'Garasi & Pool Kendaraan';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    public static function form(Schema $schema): Schema
    {
        return GarasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GarasisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGarasis::route('/'),
            'create' => CreateGarasi::route('/create'),
            'edit' => EditGarasi::route('/{record}/edit'),
        ];
    }
}
