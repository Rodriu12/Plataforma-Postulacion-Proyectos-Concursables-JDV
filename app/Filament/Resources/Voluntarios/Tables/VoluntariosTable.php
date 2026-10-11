<?php

namespace App\Filament\Resources\Voluntarios\Tables;

use App\Support\Avatar;
use App\Support\Tarjetas;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VoluntariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid(['default' => 1, 'lg' => 2, '2xl' => 3])
            ->columns([
                Stack::make([
                    Split::make([
                        ImageColumn::make('avatar')
                            ->label('')
                            ->circular()
                            ->size(48)
                            ->grow(false)
                            ->getStateUsing(fn ($record) => $record->user?->avatarUrl() ?? Avatar::url($record->nombre, 'd97706')),

                        Stack::make([
                            TextColumn::make('nombre')
                                ->weight('bold')
                                ->size('lg')
                                ->wrap()
                                ->searchable()
                                ->extraAttributes(Tarjetas::TEXTO_QUE_ENVUELVE),

                            TextColumn::make('organizacion.nombre')
                                ->label('Organización')
                                ->color('gray')
                                ->wrap()
                                ->searchable()
                                ->placeholder('Sin organización asociada')
                                ->extraAttributes(Tarjetas::TEXTO_QUE_ENVUELVE),
                        ])->space(1),
                    ])->extraAttributes(['style' => 'align-items:flex-start;']),

                    Split::make([
                        TextColumn::make('area_apoyo')
                            ->label('Área de apoyo')
                            ->badge()
                            ->grow(false)
                            ->formatStateUsing(fn (string $state) => str_replace('_', ' ', ucfirst($state))),

                        TextColumn::make('estado')
                            ->label('Estado')
                            ->badge()
                            ->grow(false)
                            ->formatStateUsing(fn (string $state) => ucfirst($state))
                            ->color(fn (string $state): string => match ($state) {
                                'pendiente' => 'warning',
                                'aprobado' => 'success',
                                'rechazado' => 'danger',
                                default => 'gray',
                            }),
                    ])->extraAttributes(['style' => 'flex-wrap:wrap;']),

                    Stack::make([
                        Tarjetas::linea('sector', 'Sector'),
                        Tarjetas::linea('disponibilidad', 'Disponibilidad'),
                        Tarjetas::linea('telefono', 'Teléfono'),
                    ])->space(1),
                ])->space(3),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente de revisión',
                        'aprobado' => 'Aprobado',
                        'rechazado' => 'Rechazado',
                    ]),
                SelectFilter::make('area_apoyo')
                    ->label('Área de apoyo')
                    ->options([
                        'rescate' => 'Rescate',
                        'logistica' => 'Logística',
                        'primeros_auxilios' => 'Primeros auxilios',
                        'comunicaciones' => 'Comunicaciones',
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
