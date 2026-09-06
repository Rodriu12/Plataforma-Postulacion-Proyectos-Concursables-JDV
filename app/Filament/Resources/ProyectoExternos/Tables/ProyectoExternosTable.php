<?php

namespace App\Filament\Resources\ProyectoExternos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ProyectoExternosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->label('Fondo Concursable')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('institucion')
                    ->label('Institución')
                    ->searchable()
                    ->badge(),

                TextColumn::make('estado_vigencia')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'abierto' => 'success',
                        'cerrado' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('fecha_cierre')
                    ->label('Cierre')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('url_fuente')
                    ->label('Enlace Oficial')
                    ->url(fn ($record) => $record->url_fuente)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->limit(30),
            ])
            ->filters([
                SelectFilter::make('estado_vigencia')
                    ->label('Estado')
                    ->options([
                        'abierto' => 'Abierto',
                        'cerrado' => 'Cerrado',
                    ]),
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
