<?php

namespace App\Filament\Resources\Garasis\Pages;

use App\Filament\Resources\Garasis\GarasiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGarasi extends EditRecord
{
    protected static string $resource = GarasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
