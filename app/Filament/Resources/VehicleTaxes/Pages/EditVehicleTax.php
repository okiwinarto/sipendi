<?php

namespace App\Filament\Resources\VehicleTaxes\Pages;

use App\Filament\Resources\VehicleTaxes\VehicleTaxResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicleTax extends EditRecord
{
    protected static string $resource = VehicleTaxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
