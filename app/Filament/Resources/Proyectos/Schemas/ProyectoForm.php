<?php

namespace App\Filament\Resources\Proyectos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class ProyectoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('organizacion_id')
                    ->label('Junta de Vecinos')
                    ->relationship('organizacion', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('titulo')
                    ->label('Título del Proyecto')
                    ->required()
                    ->maxLength(255),

                Textarea::make('descripcion')
                    ->label('Descripción y Objetivos')
                    ->rows(3)
                    ->columnSpanFull(),

                FileUpload::make('imagen_terreno')
                    ->label('Fotografía del Proyecto en Terreno (Logro Cumplido)')
                    ->image()
                    ->directory('proyectos-terreno')
                    ->columnSpanFull(),

                TextInput::make('fuente_financiamiento')
                    ->label('Fondo a Postular')
                    ->placeholder('Ej: FOSIS - Emprendamos Semilla')
                    ->maxLength(255),

                Select::make('estado')
                    ->label('Estado Actual')
                    ->options([
                        'borrador' => 'Borrador',
                        'en_postulacion' => 'En Postulación',
                        'adjudicado' => 'Adjudicado',
                        'rechazado' => 'Rechazado',
                        'en_ejecucion' => 'En Ejecución',
                        'rendido' => 'Rendido',
                    ])
                    ->required()
                    ->default('borrador'),

                TextInput::make('monto_solicitado')
                    ->label('Monto Solicitado')
                    ->numeric()
                    ->prefix('$'),

                TextInput::make('monto_adjudicado')
                    ->label('Monto Adjudicado')
                    ->numeric()
                    ->prefix('$'),

                DatePicker::make('fecha_postulacion')
                    ->label('Fecha de Postulación'),

                DatePicker::make('fecha_adjudicacion')
                    ->label('Fecha de Adjudicación'),
            ]);
    }
}
