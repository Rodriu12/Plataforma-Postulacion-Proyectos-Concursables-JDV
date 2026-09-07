<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\ProyectoExterno;
class GaleriaTerrenoWidget extends Widget
{
    protected string $view = 'filament.widgets.galeria-terreno-widget';
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2; 

    protected function getViewData(): array
    {
        return [
            'proyectos' => ProyectoExterno::whereNotNull('imagen_terreno')
                ->latest()
                ->take(6)
                ->get(),
        ];
    }
}
