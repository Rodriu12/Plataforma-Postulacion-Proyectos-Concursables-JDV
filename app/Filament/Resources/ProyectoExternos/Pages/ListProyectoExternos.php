<?php

namespace App\Filament\Resources\ProyectoExternos\Pages;

use App\Filament\Resources\ProyectoExternos\ProyectoExternoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProyectoExternos extends ListRecords
{
    protected static string $resource = ProyectoExternoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
