<?php

namespace App\Filament\Resources\InventarioItems\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MovimientosRelationManager extends RelationManager
{
    protected static string $relationship = 'movimientos';

    protected static ?string $title = 'Movimientos (kardex)';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tipo')
                ->options([
                    'entrada' => 'Entrada (compra, donación)',
                    'salida' => 'Salida (uso, pérdida, daño, descarte)',
                    'ajuste' => 'Ajuste (corrección de conteo)',
                ])
                ->required()
                ->live(),

            TextInput::make('cantidad')
                ->numeric()
                ->minValue(1)
                ->required()
                ->rules([
                    fn ($get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                        if ($get('tipo') !== 'salida') {
                            return;
                        }

                        $stockActual = $this->getOwnerRecord()->cantidad_total;

                        if ($value > $stockActual) {
                            $fail("No puedes sacar más de lo que hay en stock ({$stockActual}).");
                        }
                    },
                ]),

            TextInput::make('motivo')
                ->placeholder('Ej: Compra con fondo X, préstamo perdido, conteo físico...')
                ->required()
                ->maxLength(255),

            DateTimePicker::make('fecha')
                ->default(now())
                ->required(),

            Textarea::make('observaciones')
                ->rows(2)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('motivo')
            ->defaultSort('fecha', 'desc')
            ->columns([
                TextColumn::make('fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'entrada' => 'success',
                        'salida' => 'danger',
                        'ajuste' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('cantidad')
                    ->formatStateUsing(fn ($record) => ($record->tipo === 'salida' ? '-' : '+') . $record->cantidad),
                TextColumn::make('motivo')
                    ->wrap(),
                TextColumn::make('usuario.name')
                    ->label('Registrado por')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->options([
                        'entrada' => 'Entrada',
                        'salida' => 'Salida',
                        'ajuste' => 'Ajuste',
                    ]),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->label('Deshacer')
                    ->tooltip('Elimina el movimiento y revierte su efecto en el stock'),
            ]);
    }
}
