<?php

namespace App\Filament\Resources\Emergencias\Pages;

use App\Filament\Resources\Emergencias\EmergenciaResource;
use App\Models\Emergencia;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmergencia extends EditRecord
{
    protected static string $resource = EmergenciaResource::class;

    protected function getHeaderActions(): array
    {
        $usuario = auth()->user();

        if (! $usuario || ! in_array($usuario->role, Emergencia::ROLES_DIRECTIVA)) {
            return [];
        }

        return [
            DeleteAction::make(),
        ];
    }
}
