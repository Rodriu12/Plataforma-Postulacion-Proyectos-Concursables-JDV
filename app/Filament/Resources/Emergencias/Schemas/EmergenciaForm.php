<?php

namespace App\Filament\Resources\Emergencias\Schemas;

use App\Models\Emergencia;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmergenciaForm
{
    protected static function esDirectiva(): bool
    {
        $usuario = auth()->user();

        return $usuario && in_array($usuario->role, Emergencia::ROLES_DIRECTIVA);
    }

    protected static function esAdminCentral(): bool
    {
        return auth()->user()?->esAdminCentral() ?? false;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vecino_id')
                    ->label('Vecino que reporta')
                    ->relationship('vecino', 'nombre')
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->user()?->vecino?->id)
                    ->disabled(fn () => ! static::esDirectiva())
                    ->dehydrated()
                    ->required(),

                Select::make('organizacion_id')
                    ->label('Junta de Vecinos / Organización')
                    ->relationship('organizacion', 'nombre')
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->user()?->organizacion_id)
                    ->disabled(fn () => ! static::esAdminCentral())
                    ->dehydrated()
                    ->helperText('Se asigna automáticamente según tu organización si no la seleccionas.'),

                Select::make('voluntario_id')
                    ->label('Voluntario asignado')
                    ->relationship('voluntario', 'nombre')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => static::esDirectiva())
                    ->helperText('Solo la directiva puede asignar un voluntario a esta emergencia.'),

                Select::make('tipo')
                    ->options([
                        'incendio' => 'Incendio',
                        'accidente' => 'Accidente',
                        'emergencia_medica' => 'Emergencia médica',
                        'corte_servicio' => 'Corte de luz / agua',
                        'delincuencia' => 'Delincuencia / Seguridad',
                        'inundacion' => 'Inundación / Aluvión',
                        'otro' => 'Otro',
                    ])
                    ->required(),

                TextInput::make('ubicacion')
                    ->label('Ubicación de la emergencia')
                    ->placeholder('Calle, número o referencia del sector')
                    ->required()
                    ->maxLength(255),

                Textarea::make('descripcion')
                    ->label('Descripción de lo ocurrido')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),

                FileUpload::make('evidencia')
                    ->label('Foto o evidencia (opcional)')
                    ->image()
                    ->directory('emergencias')
                    ->openable()
                    ->columnSpanFull(),

                Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_atencion' => 'En atención',
                        'resuelta' => 'Resuelta',
                    ])
                    ->default('pendiente')
                    ->disabled(fn () => ! static::esDirectiva())
                    ->dehydrated()
                    ->required(),
            ]);
    }
}
