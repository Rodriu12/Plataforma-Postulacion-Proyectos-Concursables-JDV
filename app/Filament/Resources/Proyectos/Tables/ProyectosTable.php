<?php

namespace App\Filament\Resources\Proyectos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProyectosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->columns([
                Split::make([
                    IconColumn::make('estado')
                        ->label('')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->size('lg')
                        ->color(fn (string $state): string => match ($state) {
                            'adjudicado' => 'success',
                            'rechazado' => 'danger',
                            'en_postulacion', 'en_ejecucion' => 'warning',
                            'rendido' => 'info',
                            default => 'gray',
                        })
                        ->grow(false),

                    Stack::make([
                        TextColumn::make('titulo')
                            ->weight('bold')
                            ->size('lg')
                            ->wrap()
                            ->searchable(),

                        TextColumn::make('organizacion.nombre')
                            ->color('gray')
                            ->wrap()
                            ->searchable(),

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
                            ->color('gray')
                            ->visibleFrom('md'),

                        TextColumn::make('monto_solicitado')
                            ->label('Solicitado')
                            ->numeric()
                            ->visibleFrom('md'),

                        TextColumn::make('monto_adjudicado')
                            ->label('Adjudicado')
                            ->numeric()
                            ->visibleFrom('md'),

                        TextColumn::make('fecha_postulacion')
                            ->date()
                            ->color('gray')
                            ->size('xs'),
                    ])->space(1),
                ]),
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
