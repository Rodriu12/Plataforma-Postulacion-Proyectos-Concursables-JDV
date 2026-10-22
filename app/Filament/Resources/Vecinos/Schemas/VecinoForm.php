<?php

namespace App\Filament\Resources\Vecinos\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class VecinoForm
{
    protected static function esDirectiva(): bool
    {
        $usuario = auth()->user();

        return $usuario && in_array($usuario->role, User::ROLES_DIRECTIVA);
    }

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
                    ->maxLength(12)
                    ->disabled(fn () => ! static::esDirectiva())
                    ->dehydrated(),
                    
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
                    ->disk('uploads')
                    ->directory('comprobantes-domicilio')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                    ->rules(['file', 'mimes:pdf,jpeg,png,jpg,webp', 'max:5120'])
                    ->maxSize(5120)
                    ->helperText('Solo documentos PDF o imágenes JPG, PNG o WEBP (máx. 5 MB). No se permiten GIFs, audio ni otros formatos no autorizados.')
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
                    ->disabled(fn () => ! static::esDirectiva())
                    ->dehydrated()
                    ->required(),
            ]);
    }
}
