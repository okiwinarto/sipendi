<?php

namespace App\Filament\Resources\ReminderSettings;

use App\Filament\Resources\ReminderSettings\Pages\CreateReminderSetting;
use App\Filament\Resources\ReminderSettings\Pages\EditReminderSetting;
use App\Filament\Resources\ReminderSettings\Pages\ListReminderSettings;
use App\Filament\Resources\ReminderSettings\Schemas\ReminderSettingForm;
use App\Filament\Resources\ReminderSettings\Tables\ReminderSettingsTable;
use App\Models\ReminderSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReminderSettingResource extends Resource
{
    protected static ?string $model = ReminderSetting::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Pengaturan Sistem';

    protected static ?string $navigationLabel = 'Ambang Pengingat';

    protected static ?string $modelLabel = 'Konfigurasi Pengingat';

    protected static ?string $pluralModelLabel = 'Konfigurasi Ambang Pengingat';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBellAlert;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin_it') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return ReminderSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReminderSettingsTable::configure($table);
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
            'index' => ListReminderSettings::route('/'),
            'create' => CreateReminderSetting::route('/create'),
            'edit' => EditReminderSetting::route('/{record}/edit'),
        ];
    }
}
