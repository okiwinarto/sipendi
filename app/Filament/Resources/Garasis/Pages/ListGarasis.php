<?php

namespace App\Filament\Resources\Garasis\Pages;

use App\Filament\Resources\Garasis\GarasiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGarasis extends ListRecords
{
    protected static string $resource = GarasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
