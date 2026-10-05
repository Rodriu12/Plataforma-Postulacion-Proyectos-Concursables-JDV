<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Imports\ResidenteImporter;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('Importar planilla (Excel/CSV)')
                ->importer(ResidenteImporter::class)
                ->visible(fn () => in_array(auth()->user()?->role, User::ROLES_GESTION_USUARIOS))
                // Sin forzar un separador fijo: Filament detecta solo si el
                // archivo subido usa ',' o ';' (Excel en español suele usar
                // ';', pero forzarlo rompía la subida si el archivo real
                // traía otro separador).
                ->options([
                    'organizacion_id' => auth()->user()?->organizacion_id,
                ]),
            CreateAction::make()
                ->createAnother(false),
        ];
    }
}
