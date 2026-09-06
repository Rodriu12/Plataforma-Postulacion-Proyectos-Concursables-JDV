<?php

namespace App\Filament\Resources\Proyectos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProyectoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('organizacion_id')
                    ->relationship('organizacion', 'id')
                    ->required(),
                TextInput::make('titulo')
                    ->required(),
                Textarea::make('descripcion')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('fuente_financiamiento')
                    ->default(null),
                TextInput::make('monto_solicitado')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('monto_adjudicado')
                    ->numeric()
                    ->default(null),
                Select::make('estado')
                    ->options([
            'borrador' => 'Borrador',
            'en_postulacion' => 'En postulacion',
            'adjudicado' => 'Adjudicado',
            'rechazado' => 'Rechazado',
            'en_ejecucion' => 'En ejecucion',
            'rendido' => 'Rendido',
        ])
                    ->default('borrador')
                    ->required(),
                DatePicker::make('fecha_postulacion'),
                DatePicker::make('fecha_adjudicacion'),
            ]);
    }
}
