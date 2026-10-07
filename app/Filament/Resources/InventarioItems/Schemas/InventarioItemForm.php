<?php

namespace App\Filament\Resources\InventarioItems\Schemas;

use App\Models\Proyecto;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventarioItemForm
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
                    ->dehydrated()
                    ->helperText(fn () => static::esAdminCentral() ? null : 'Se asigna automáticamente a tu organización.'),

                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),

                Select::make('categoria')
                    ->options([
                        'mobiliario' => 'Mobiliario',
                        'herramientas' => 'Herramientas',
                        'materiales' => 'Materiales',
                        'equipamiento' => 'Equipamiento',
                        'otro' => 'Otro',
                    ])
                    ->default('otro')
                    ->required(),

                Select::make('proyecto_id')
                    ->label('Comprado con fondos del proyecto')
                    ->relationship(
                        'proyecto',
                        'titulo',
                        modifyQueryUsing: fn ($query) => $query->when(
                            ! static::esAdminCentral(),
                            fn ($q) => $q->where('organizacion_id', auth()->user()?->organizacion_id)
                        ),
                    )
                    ->searchable()
                    ->preload()
                    ->helperText('Déjalo vacío si es un insumo general de bodega, no ligado a un proyecto.'),

                TextInput::make('unidad')
                    ->label('Unidad de medida')
                    ->default('unidad(es)')
                    ->placeholder('unidad(es), cajas, kg...')
                    ->required()
                    ->maxLength(50),

                TextInput::make('cantidad_total')
                    ->label('Cantidad inicial')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required()
                    ->visibleOn('create')
                    ->helperText('Una vez creado el ítem, ajusta la cantidad desde la pestaña "Movimientos" para mantener el historial.'),

                TextInput::make('ubicacion')
                    ->label('Ubicación / Bodega')
                    ->placeholder('Ej: Sede social, bodega municipal...')
                    ->maxLength(255),

                Select::make('estado')
                    ->options([
                        'disponible' => 'Disponible',
                        'agotado' => 'Agotado',
                        'de_baja' => 'De baja',
                    ])
                    ->default('disponible')
                    ->required(),

                Textarea::make('descripcion')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
