<?php

namespace App\Filament\Resources\Vecinos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class VecinosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('rut')
                    ->searchable(),
                TextColumn::make('direccion')
                    ->searchable(),
                TextColumn::make('estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'aprobado' => 'success',
                        'rechazado' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => ucfirst($state)),
                TextColumn::make('certificado')
                    ->label('Certificado')
                    // 1. Mostramos texto distinto si está aprobado o no
                    ->getStateUsing(fn ($record) => $record?->estado === 'aprobado' ? 'Descargar' : 'No disponible')
                    // 2. Azul si está aprobado, gris si no lo está
                    ->color(fn ($record) => $record?->estado === 'aprobado' ? 'primary' : 'gray')
                    ->weight('bold')
                    // 3. Solo generamos la ruta si está aprobado, sino queda sin enlace (null)
                    ->url(fn ($record) => $record?->estado === 'aprobado' ? route('vecino.certificado', $record->id) : null)
                    ->openUrlInNewTab(),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendientes',
                        'aprobado' => 'Aprobados',
                        'rechazado' => 'Rechazados',
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
