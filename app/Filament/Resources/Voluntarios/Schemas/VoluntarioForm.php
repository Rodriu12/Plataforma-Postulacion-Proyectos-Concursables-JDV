<?php

namespace App\Filament\Resources\Voluntarios\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VoluntarioForm
{
    protected static function esDirectiva(): bool
    {
        $usuario = auth()->user();

        return $usuario && in_array($usuario->role, User::ROLES_DIRECTIVA);
    }

    protected static function esAdminCentral(): bool
    {
        return auth()->user()?->esAdminCentral() ?? false;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),

                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(15),

                Select::make('organizacion_id')
                    ->label('Junta de Vecinos / Organización a la que apoya')
                    ->relationship('organizacion', 'nombre')
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->user()?->organizacion_id)
                    ->disabled(fn () => ! static::esAdminCentral())
                    ->dehydrated(),

                TextInput::make('sector')
                    ->label('Sector o Villa')
                    ->placeholder('Ej: Cerro Parra, Los Copihues...')
                    ->maxLength(255),

                Select::make('area_apoyo')
                    ->label('Área de apoyo')
                    ->options([
                        'rescate' => 'Rescate',
                        'logistica' => 'Logística',
                        'primeros_auxilios' => 'Primeros auxilios',
                        'comunicaciones' => 'Comunicaciones',
                        'otro' => 'Otro',
                    ])
                    ->required(),

                TextInput::make('disponibilidad')
                    ->label('Disponibilidad (días / horario)')
                    ->placeholder('Ej: Fines de semana, tardes')
                    ->maxLength(255),

                Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente de revisión',
                        'aprobado' => 'Aprobado',
                        'rechazado' => 'Rechazado',
                    ])
                    ->default('pendiente')
                    ->disabled(fn () => ! static::esDirectiva())
                    ->dehydrated()
                    ->required(),
            ]);
    }
}
