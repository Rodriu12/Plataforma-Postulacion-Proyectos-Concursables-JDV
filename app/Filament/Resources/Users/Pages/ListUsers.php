<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Imports\ResidenteImporter;
use App\Filament\Resources\Users\UserResource;
use App\Models\Organizacion;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
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
                ->optionsFormComponents(fn () => auth()->user()?->esAdminCentral() ? [
                    Select::make('organizacion_id')
                        ->label('Asignar a Organización / Junta de Vecinos')
                        ->options(Organizacion::pluck('nombre', 'id'))
                        ->searchable()
                        ->placeholder('Sin asignar (dejar pendiente para asignar después)')
                        ->helperText('Opcional: Si se deja sin asignar, las personas se crearán inactivas y no recibirán correo de contraseña hasta que un administrador les asigne una junta.'),
                ] : [])
                ->importerOptions(fn () => [
                    'organizacion_id' => auth()->user()?->organizacion_id,
                ])
                ->afterImport(function ($data, $livewire, $action, $result) {
                    $total = $result->created + $result->updated;
                    $mensaje = "Se crearon o actualizaron {$total} fila(s).";

                    if ($result->failed()) {
                        $mensaje .= ' ' . count($result->failures) . ' fila(s) fallaron — revisa el archivo descargable con el detalle del error.';
                    }

                    if (auth()->user()?->esAdminCentral() && empty($data['organizacion_id'] ?? null)) {
                        $mensaje .= ' Nota: Las personas importadas sin organización permanecerán inactivas hasta que se les asigne una junta u organización.';
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
