<?php

namespace App\Filament\Resources\ProyectoExternos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
class ProyectoExternoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('titulo')
                ->label('Título del Fondo')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
                TextInput::make('institucion')
                ->label('Institución Pública / Programa')
                ->maxLength(255),
                TextInput::make('url_fuente')
                ->label('Enlace Para Postular')
                ->url()
                ->required()
                ->maxLength(255),
                DatePicker::make('fecha_cierre')
                ->label('Fecha de Cierre de Postulación'),
                Select::make('estado_vigencia')
                ->label('Estado de Vigencia')
                ->options([
                    'abierto' => 'Abierto',
                    'por_abrir' => 'Por abrir',
                    'cerrado' => 'Cerrado',
                ])
                ->required()
                ->default('abierto'),
                Textarea::make('descripcion')
                ->label('Descripción / Resumen')
                ->columnSpanFull(),
            ]);
    }
}
