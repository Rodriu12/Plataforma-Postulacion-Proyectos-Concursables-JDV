<?php

namespace App\Filament\Resources\ProyectoExternos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
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
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'abierto' => 'Abierto',
                        'por_abrir' => 'Por abrir',
                        'cerrado' => 'Cerrado',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'abierto' => 'success',
                        'por_abrir' => 'warning',
                        'cerrado' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('fecha_cierre')
                    ->label('Cierre')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('url_fuente')
                    ->label('Enlace Oficial')
                    ->url(fn($record) => $record->url_fuente)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->limit(30),
            ])
            ->filters([
                SelectFilter::make('estado_vigencia')
                    ->label('Estado')
                    ->options([
                        'abierto' => 'Abierto',
                        'por_abrir' => 'Por abrir',
                        'cerrado' => 'Cerrado',
                    ]),
                SelectFilter::make('mes_proyecto')
                    ->label('Filtrar por Mes')
                    ->options([
                        '01' => 'Enero',
                        '02' => 'Febrero',
                        '03' => 'Marzo',
                        '04' => 'Abril',
                        '05' => 'Mayo',
                        '06' => 'Junio',
                        '07' => 'Julio',
                        '08' => 'Agosto',
                        '09' => 'Septiembre',
                        '10' => 'Octubre',
                        '11' => 'Noviembre',
                        '12' => 'Diciembre',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn(Builder $query, $value): Builder => $query->whereMonth('fecha_cierre', $value)
                        );
                    })
                    ->indicator('Mes'),
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
