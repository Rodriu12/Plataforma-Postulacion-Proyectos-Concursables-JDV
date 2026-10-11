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
                TextColumn::make('titulo')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('organizacion.nombre')
                    ->searchable()
                    ->toggleable(),

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

                TextColumn::make('fuente_financiamiento')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('monto_solicitado')
                    ->label('Solicitado')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('monto_adjudicado')
                    ->label('Adjudicado')
                    ->numeric()
                    ->toggleable(),

                TextColumn::make('fecha_postulacion')
                    ->date()
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
