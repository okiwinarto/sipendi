<?php

namespace App\Filament\Resources\VehicleTaxes;

use App\Filament\Resources\VehicleTaxes\Pages\CreateVehicleTax;
use App\Filament\Resources\VehicleTaxes\Pages\EditVehicleTax;
use App\Filament\Resources\VehicleTaxes\Pages\ListVehicleTaxes;
use App\Filament\Resources\VehicleTaxes\Schemas\VehicleTaxForm;
use App\Filament\Resources\VehicleTaxes\Tables\VehicleTaxesTable;
use App\Models\VehicleTax;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VehicleTaxResource extends Resource
{
    protected static ?string $model = VehicleTax::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Pemeliharaan & Kepatuhan';

    protected static ?string $navigationLabel = 'Pajak & KIR';

    protected static ?string $modelLabel = 'Data Pajak & KIR';

    protected static ?string $pluralModelLabel = 'Kepatuhan Pajak & Uji KIR';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return VehicleTaxForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleTaxesTable::configure($table);
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
            'index' => ListVehicleTaxes::route('/'),
            'create' => CreateVehicleTax::route('/create'),
            'edit' => EditVehicleTax::route('/{record}/edit'),
        ];
    }
}
