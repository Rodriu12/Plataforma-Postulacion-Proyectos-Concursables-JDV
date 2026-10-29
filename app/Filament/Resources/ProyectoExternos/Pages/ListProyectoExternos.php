<?php

namespace App\Filament\Resources\ProyectoExternos\Pages;

use App\Filament\Resources\ProyectoExternos\ProyectoExternoResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Artisan;

class ListProyectoExternos extends ListRecords
{
    protected static string $resource = ProyectoExternoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('actualizarFondos')
                ->label('Actualizar')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    Artisan::call('app:sincronizar-fondos-gob');

                    Notification::make()
                        ->title('Fondos actualizados')
                        ->body('La sincronización de proyectos externos se completó correctamente.')
                        ->success()
                        ->send();
                })
                ->extraAttributes([
                    'style' => 'display:inline-flex; align-items:center; gap:8px; padding:8px 16px; border-radius:9999px; border:1px solid #bfdbfe; font-size:14px; font-weight:600; cursor:pointer; background:#eff6ff; color:#1d4ed8; transition:background 0.15s, box-shadow 0.15s; box-shadow:none;',
                ]),
            CreateAction::make(),
        ];
    }
}
