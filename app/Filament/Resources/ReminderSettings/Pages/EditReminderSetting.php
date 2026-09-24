<?php

namespace App\Filament\Resources\ReminderSettings\Pages;

use App\Filament\Resources\ReminderSettings\ReminderSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReminderSetting extends EditRecord
{
    protected static string $resource = ReminderSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
