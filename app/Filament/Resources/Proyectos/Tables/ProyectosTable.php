<?php

namespace App\Filament\Resources\Proyectos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProyectosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('organizacion.nombre')
                    ->searchable(),
                TextColumn::make('titulo')
                    ->searchable(),
                TextColumn::make('fuente_financiamiento')
                    ->searchable(),
                TextColumn::make('monto_solicitado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('monto_adjudicado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'borrador' => 'Borrador',
                        'en_postulacion' => 'En Postulación',
                        'adjudicado' => 'Adjudicado',
                        'rechazado' => 'Rechazado',
                        'en_ejecucion' => 'En Ejecución',
                        'rendido' => 'Rendido',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'adjudicado' => 'success',
                        'rechazado' => 'danger',
                        'en_postulacion', 'en_ejecucion' => 'warning',
                        'rendido' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('fecha_postulacion')
                    ->date()
                    ->sortable(),
                TextColumn::make('fecha_adjudicacion')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
