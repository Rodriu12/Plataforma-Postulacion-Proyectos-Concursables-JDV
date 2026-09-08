<?php

namespace App\Filament\Resources\Organizaciones\Pages;

use App\Filament\Resources\Organizaciones\OrganizacionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrganizacion extends ViewRecord
{
    protected static string $resource = OrganizacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
