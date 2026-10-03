<?php

namespace App\Filament\Resources\InventarioItems\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PrestamosRelationManager extends RelationManager
{
    protected static string $relationship = 'prestamos';

    protected static ?string $title = 'Préstamos';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('vecino_id')
                ->label('Prestado a (vecino)')
                ->relationship(
                    'vecino',
                    'nombre',
                    modifyQueryUsing: fn ($query) => $query->whereHas(
                        'user',
                        fn ($q) => $q->where('organizacion_id', $this->getOwnerRecord()->organizacion_id)
                    ),
                )
                ->searchable()
                ->preload()
                ->live()
                ->disabled(fn ($get) => filled($get('voluntario_id')))
                ->helperText('Completa este o el de voluntario, no ambos.'),

            Select::make('voluntario_id')
                ->label('Prestado a (voluntario)')
                ->relationship(
                    'voluntario',
                    'nombre',
                    modifyQueryUsing: fn ($query) => $query->where('organizacion_id', $this->getOwnerRecord()->organizacion_id),
                )
                ->searchable()
                ->preload()
                ->live()
                ->disabled(fn ($get) => filled($get('vecino_id'))),

            TextInput::make('cantidad')
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->required()
                ->rules([
                    fn () => function (string $attribute, $value, \Closure $fail) {
                        $disponible = $this->getOwnerRecord()->cantidad_disponible;

                        if ($value > $disponible) {
                            $fail("No hay suficiente stock disponible ({$disponible}).");
                        }
                    },
                ]),

            DatePicker::make('fecha_prestamo')
                ->default(now())
                ->required(),

            DatePicker::make('fecha_devolucion_esperada')
                ->label('Devolución esperada'),

            DatePicker::make('fecha_devolucion_real')
                ->label('Devolución real')
                ->helperText('Complétalo cuando te devuelvan el ítem; el estado pasa a "Devuelto" automáticamente.'),

            Select::make('estado')
                ->options([
                    'prestado' => 'Prestado',
                    'devuelto' => 'Devuelto',
                    'atrasado' => 'Atrasado',
                ])
                ->default('prestado')
                ->required(),

            Textarea::make('observaciones')
                ->rows(2)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->defaultSort('fecha_prestamo', 'desc')
            ->columns([
                TextColumn::make('quien')
                    ->label('Prestado a')
                    ->state(fn ($record) => $record->vecino?->nombre ?? $record->voluntario?->nombre ?? '—'),
                TextColumn::make('cantidad'),
                TextColumn::make('fecha_prestamo')
                    ->label('Préstamo')
                    ->date('d/m/Y'),
                TextColumn::make('fecha_devolucion_esperada')
                    ->label('Devolución esperada')
                    ->date('d/m/Y')
                    ->placeholder('—'),
                TextColumn::make('estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'prestado' => 'warning',
                        'devuelto' => 'success',
                        'atrasado' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->options([
                        'prestado' => 'Prestado',
                        'devuelto' => 'Devuelto',
                        'atrasado' => 'Atrasado',
                    ]),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
