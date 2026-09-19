<?php

namespace App\Filament\Resources\Organizaciones\Pages;

use App\Filament\Resources\Organizaciones\OrganizacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrganizacion extends ListRecords
{
    protected static string $resource = OrganizacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
