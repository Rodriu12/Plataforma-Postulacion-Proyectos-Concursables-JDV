<?php

namespace App\Filament\Resources\Emergencias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmergenciasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->columns([
                Split::make([
                    IconColumn::make('tipo')
                        ->label('')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->size('lg')
                        ->color(fn (string $state): string => match ($state) {
                            'incendio', 'delincuencia' => 'danger',
                            'accidente', 'emergencia_medica', 'inundacion' => 'warning',
                            default => 'gray',
                        })
                        ->grow(false),

                    Stack::make([
                        TextColumn::make('tipo')
                            ->weight('bold')
                            ->size('lg')
                            ->wrap()
                            ->badge()
                            ->formatStateUsing(fn (string $state) => str_replace('_', ' ', ucfirst($state)))
                            ->color(fn (string $state): string => match ($state) {
                                'incendio', 'delincuencia' => 'danger',
                                'accidente', 'emergencia_medica', 'inundacion' => 'warning',
                                default => 'gray',
                            }),

                        TextColumn::make('ubicacion')
                            ->color('gray')
                            ->wrap()
                            ->searchable(),

                        TextColumn::make('vecino.nombre')
                            ->label('Reportado por')
                            ->searchable()
                            ->placeholder('Sin vecino asociado'),

                        TextColumn::make('voluntario.nombre')
                            ->label('Voluntario asignado')
                            ->placeholder('Sin asignar')
                            ->visibleFrom('md'),

                        TextColumn::make('organizacion.nombre')
                            ->label('Organización')
                            ->color('gray')
                            ->searchable()
                            ->visibleFrom('md'),

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
                            ->color('gray')
                            ->size('xs'),
                    ])->space(1),
                ]),
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
