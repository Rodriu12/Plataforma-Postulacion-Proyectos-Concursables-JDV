<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;
use App\Models\Proyecto;
class GaleriaTerrenoWidget extends Widget
{
    protected string $view = 'filament.widgets.galeria-terreno-widget';
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2; 

    public static function canView(): bool
    {
        $usuario = auth()->user();

        return $usuario && in_array($usuario->role, User::ROLES_DIRECTIVA);
    }

    protected function getViewData(): array
    {
        return [
            'proyectos' => Proyecto::whereNotNull('imagen_terreno')
                ->latest()
                ->take(6)
                ->get(),
        ];
    }
}
