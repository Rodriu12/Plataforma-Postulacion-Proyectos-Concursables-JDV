<?php

namespace App\Filament\Resources\InventarioItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InventarioItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('nombre')
            ->columns([
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('categoria')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),
                TextColumn::make('cantidad_total')
                    ->label('Total')
                    ->numeric()
                    ->sortable(),
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
                    ->placeholder('Sin especificar')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('proyecto.titulo')
                    ->label('Proyecto')
                    ->placeholder('Bodega general')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('organizacion.nombre')
                    ->label('Organización')
                    ->searchable()
                    ->toggleable(),
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
