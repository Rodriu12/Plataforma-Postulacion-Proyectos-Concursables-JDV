<?php

namespace App\Filament\Resources\Organizacions\Pages;

use App\Filament\Resources\Organizacions\OrganizacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrganizacions extends ListRecords
{
    protected static string $resource = OrganizacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
