<?php

namespace App\Filament\Resources\Vecinos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class VecinoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('rut')
                    ->label('RUT')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(12),
                    
                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(15),
                    
                TextInput::make('direccion')
                    ->label('Dirección Completa')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('sector')
                    ->label('Sector o Villa')
                    ->placeholder('Ej: Cerro Parra, Los Copihues...')
                    ->required()
                    ->maxLength(255),
                    
                FileUpload::make('comprobante_domicilio')
                    ->label('Comprobante de Domicilio (Boleta Luz/Agua)')
                    ->directory('comprobantes-domicilio')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                    ->openable() 
                    ->downloadable()
                    ->columnSpanFull(),
                    
                Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente de Revisión',
                        'aprobado' => 'Aprobado (Vecino Activo)',
                        'rechazado' => 'Rechazado',
                    ])
                    ->default('pendiente')
                    ->required(),
            ]);
    }
}
