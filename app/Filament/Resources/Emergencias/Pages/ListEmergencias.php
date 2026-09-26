<?php

namespace App\Filament\Resources\Emergencias\Pages;

use App\Filament\Resources\Emergencias\EmergenciaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmergencias extends ListRecords
{
    protected static string $resource = EmergenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Reportar emergencia'),
        ];
    }
}
