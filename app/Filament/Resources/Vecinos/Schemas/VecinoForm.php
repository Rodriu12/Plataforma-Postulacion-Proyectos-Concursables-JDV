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
                    ->label('Comprobante de Domicilio (PDF)')
                    ->disk('uploads')
                    ->directory('comprobantes-domicilio')
                    ->acceptedFileTypes(['application/pdf'])
                    ->rules(['file', 'mimes:pdf', 'max:5120'])
                    ->maxSize(5120)
                    ->helperText('Solo se aceptan archivos PDF. El documento será revisado mediante OCR para validar que corresponda al vecino y a la dirección registrada.')
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull(),

                TextInput::make('comprobante_validado')
                    ->label('Estado OCR')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(function ($state, $record) {
                        if (blank($record?->comprobante_domicilio)) {
                            return 'Sin comprobante cargado';
                        }

                        if ($record?->comprobante_validado === true) {
                            return 'Válido';
                        }

                        if ($record?->comprobante_validado === false) {
                            return 'No coincide';
                        }

                        return 'Pendiente de validación';
                    })
                    ->extraAttributes(function ($record) {
                        $color = 'background-color: #f8fafc; color: #0f172a;';

                        if (blank($record?->comprobante_domicilio)) {
                            $color = 'background-color: #f8fafc; color: #475569;';
                        } elseif ($record?->comprobante_validado === true) {
                            $color = 'background-color: #ecfdf5; color: #166534;';
                        } elseif ($record?->comprobante_validado === false) {
                            $color = 'background-color: #fef2f2; color: #991b1b;';
                        } else {
                            $color = 'background-color: #fff7ed; color: #9a5b00;';
                        }

                        return ['style' => $color];
                    }),

                TextInput::make('comprobante_motivo')
                    ->label('Resultado OCR')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($state, $record) => $record?->comprobante_motivo ?: 'Sin resultado de validación OCR disponible.')
                    ->extraAttributes(function ($record) {
                        $color = 'background-color: #f8fafc; color: #0f172a;';

                        if (blank($record?->comprobante_domicilio)) {
                            $color = 'background-color: #f8fafc; color: #475569;';
                        } elseif ($record?->comprobante_validado === true) {
                            $color = 'background-color: #ecfdf5; color: #166534;';
                        } elseif ($record?->comprobante_validado === false) {
                            $color = 'background-color: #fef2f2; color: #991b1b;';
                        } else {
                            $color = 'background-color: #fff7ed; color: #9a5b00;';
                        }

                        return ['style' => $color];
                    }),

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
