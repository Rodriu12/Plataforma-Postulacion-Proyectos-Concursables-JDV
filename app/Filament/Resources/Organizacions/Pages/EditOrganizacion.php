<?php

namespace App\Filament\Resources\Organizacions\Pages;

use App\Filament\Resources\Organizacions\OrganizacionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrganizacion extends EditRecord
{
    protected static string $resource = OrganizacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
