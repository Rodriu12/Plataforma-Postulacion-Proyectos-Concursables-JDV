<?php

namespace App\Filament\Resources\Reuniones\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReunionesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('fecha_hora', 'desc')
            ->columns([
                TextColumn::make('titulo')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('fecha_hora')
                    ->label('Fecha y hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('lugar')
                    ->searchable(),
                TextColumn::make('organizacion.nombre')
                    ->label('Organización')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'programada' => 'info',
                        'realizada' => 'success',
                        'cancelada' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('convocadaPor.name')
                    ->label('Convocada por')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->options([
                        'programada' => 'Programada',
                        'realizada' => 'Realizada',
                        'cancelada' => 'Cancelada',
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
