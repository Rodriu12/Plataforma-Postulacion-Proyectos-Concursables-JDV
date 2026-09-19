<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\ProyectoExterno;

class ProyectosNovedadesWidget extends Widget
{
    protected string $view = 'filament.widgets.proyectos-novedades-widget';
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected function getViewData(): array
    {
        return [
            'proyectos' => ProyectoExterno::latest()->take(3)->get(),
        ];
    }
}
