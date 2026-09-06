<?php

namespace App\Filament\Resources\ProyectoExternos\Pages;

use App\Filament\Resources\ProyectoExternos\ProyectoExternoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProyectoExterno extends EditRecord
{
    protected static string $resource = ProyectoExternoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
