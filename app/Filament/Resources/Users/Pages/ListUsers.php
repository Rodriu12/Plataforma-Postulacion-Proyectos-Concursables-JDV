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
                // Excel en español usa ';' como separador de columnas en CSV,
                // no ',' — con ',' todo se ve amontonado en una sola columna.
                ->csvDelimiter(';')
                ->options([
                    'organizacion_id' => auth()->user()?->organizacion_id,
                ]),
            CreateAction::make(),
        ];
    }
}
