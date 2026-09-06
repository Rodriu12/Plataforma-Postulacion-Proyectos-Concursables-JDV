<?php

namespace App\Filament\Resources\ProyectoExternos\Pages;

use App\Filament\Resources\ProyectoExternos\ProyectoExternoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProyectoExterno extends ViewRecord
{
    protected static string $resource = ProyectoExternoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
