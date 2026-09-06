<?php

namespace App\Filament\Resources\Organizacions\Pages;

use App\Filament\Resources\Organizacions\OrganizacionResource;
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
