<?php

namespace App\Filament\Resources\Reuniones\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReunionForm
{
    protected static function esAdminCentral(): bool
    {
        return auth()->user()?->esAdminCentral() ?? false;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('organizacion_id')
                    ->label('Organización')
                    ->relationship('organizacion', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->user()?->organizacion_id)
                    ->disabled(fn () => ! static::esAdminCentral())
                    ->dehydrated(),

                TextInput::make('titulo')
                    ->required()
                    ->maxLength(255),

                DateTimePicker::make('fecha_hora')
                    ->label('Fecha y hora')
                    ->required()
                    ->native(false),

                TextInput::make('lugar')
                    ->placeholder('Ej: Sede social, Cerro Parra')
                    ->required()
                    ->maxLength(255),

                Select::make('estado')
                    ->options([
                        'programada' => 'Programada',
                        'realizada' => 'Realizada',
                        'cancelada' => 'Cancelada',
                    ])
                    ->default('programada')
                    ->required(),

                Textarea::make('descripcion')
                    ->label('Tabla / temas a tratar')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
