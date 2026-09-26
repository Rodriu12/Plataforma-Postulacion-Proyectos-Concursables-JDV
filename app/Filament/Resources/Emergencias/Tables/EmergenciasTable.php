<?php

namespace App\Filament\Resources\Emergencias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmergenciasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => str_replace('_', ' ', ucfirst($state)))
                    ->color(fn (string $state): string => match ($state) {
                        'incendio', 'delincuencia' => 'danger',
                        'accidente', 'emergencia_medica', 'inundacion' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('vecino.nombre')
                    ->label('Reportado por')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Sin vecino asociado'),
                TextColumn::make('organizacion.nombre')
                    ->label('Organización')
                    ->searchable()
                    ->placeholder('Sin organización asociada'),
                TextColumn::make('ubicacion')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => str_replace('_', ' ', ucfirst($state)))
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'danger',
                        'en_atencion' => 'warning',
                        'resuelta' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Reportado el')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_atencion' => 'En atención',
                        'resuelta' => 'Resuelta',
                    ]),
                SelectFilter::make('tipo')
                    ->options([
                        'incendio' => 'Incendio',
                        'accidente' => 'Accidente',
                        'emergencia_medica' => 'Emergencia médica',
                        'corte_servicio' => 'Corte de luz / agua',
                        'delincuencia' => 'Delincuencia / Seguridad',
                        'inundacion' => 'Inundación / Aluvión',
                        'otro' => 'Otro',
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
