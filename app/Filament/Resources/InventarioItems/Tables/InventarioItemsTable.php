<?php

namespace App\Filament\Resources\InventarioItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InventarioItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('nombre')
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->columns([
                Split::make([
                    IconColumn::make('categoria')
                        ->label('')
                        ->icon(fn (string $state): string => match ($state) {
                            'mobiliario' => 'heroicon-o-squares-2x2',
                            'herramientas' => 'heroicon-o-wrench-screwdriver',
                            'materiales' => 'heroicon-o-cube',
                            'equipamiento' => 'heroicon-o-cog-6-tooth',
                            default => 'heroicon-o-archive-box',
                        })
                        ->size('lg')
                        ->color('primary')
                        ->grow(false),

                    Stack::make([
                        TextColumn::make('nombre')
                            ->weight('bold')
                            ->size('lg')
                            ->wrap()
                            ->searchable(),

                        TextColumn::make('organizacion.nombre')
                            ->label('Organización')
                            ->color('gray')
                            ->wrap()
                            ->searchable()
                            ->toggleable(),

                        TextColumn::make('cantidad_total')
                            ->label('Total')
                            ->numeric(),

                        TextColumn::make('cantidad_prestada')
                            ->label('Prestado')
                            ->state(fn ($record) => $record->cantidad_prestada)
                            ->color(fn ($state) => $state > 0 ? 'warning' : 'gray'),

                        TextColumn::make('cantidad_disponible')
                            ->label('Disponible')
                            ->state(fn ($record) => $record->cantidad_disponible)
                            ->badge()
                            ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),

                        TextColumn::make('ubicacion')
                            ->color('gray')
                            ->placeholder('Sin especificar')
                            ->visibleFrom('md'),

                        TextColumn::make('proyecto.titulo')
                            ->label('Proyecto')
                            ->placeholder('Bodega general')
                            ->visibleFrom('md'),

                        TextColumn::make('estado')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => match ($state) {
                                'disponible' => 'Disponible',
                                'agotado' => 'Agotado',
                                'de_baja' => 'De baja',
                                default => ucfirst($state),
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'disponible' => 'success',
                                'agotado' => 'warning',
                                'de_baja' => 'danger',
                                default => 'gray',
                            }),
                    ])->space(1),
                ]),
            ])
            ->filters([
                SelectFilter::make('categoria')
                    ->options([
                        'mobiliario' => 'Mobiliario',
                        'herramientas' => 'Herramientas',
                        'materiales' => 'Materiales',
                        'equipamiento' => 'Equipamiento',
                        'otro' => 'Otro',
                    ]),
                SelectFilter::make('estado')
                    ->options([
                        'disponible' => 'Disponible',
                        'agotado' => 'Agotado',
                        'de_baja' => 'De baja',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
