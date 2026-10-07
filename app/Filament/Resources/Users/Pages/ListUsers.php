<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Imports\ResidenteImporter;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use RomanSulzhyk\FilamentImport\Actions\ExcelImportAction;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->label('Importar planilla (Excel/CSV)')
                ->importer(ResidenteImporter::class)
                ->visible(fn () => in_array(auth()->user()?->role, User::ROLES_GESTION_USUARIOS))
                ->importerOptions([
                    'organizacion_id' => auth()->user()?->organizacion_id,
                ])
                ->afterImport(function ($data, $livewire, $action, $result) {
                    $mensaje = "Se crearon o actualizaron {$result->created} + {$result->updated} fila(s).";

                    if ($result->failed()) {
                        $mensaje .= ' ' . count($result->failures) . ' fila(s) fallaron — revisa el archivo descargable.';
                    }

                    Notification::make()
                        ->title('Importación completada')
                        ->body($mensaje)
                        ->success()
                        ->send();
                }),
            CreateAction::make()
                ->createAnother(false),
        ];
    }
}
