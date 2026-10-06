<?php

namespace App\Filament\Resources\Voluntarios\Tables;

use App\Support\Avatar;
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
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->columns([
                Split::make([
                    ImageColumn::make('avatar')
                        ->label('')
                        ->circular()
                        ->size(56)
                        ->getStateUsing(fn ($record) => $record->user?->avatarUrl() ?? Avatar::url($record->nombre, 'd97706'))
                        ->grow(false),

                    Stack::make([
                        TextColumn::make('nombre')
                            ->weight('bold')
                            ->size('lg')
                            ->wrap()
                            ->searchable(),

                        TextColumn::make('organizacion.nombre')
                            ->label('Organización')
                            ->color('gray')
                            ->wrap()
                            ->searchable()
                            ->placeholder('Sin organización asociada'),

                        TextColumn::make('area_apoyo')
                            ->label('Área de apoyo')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => str_replace('_', ' ', ucfirst($state))),

                        TextColumn::make('estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pendiente' => 'warning',
                                'aprobado' => 'success',
                                'rechazado' => 'danger',
                                default => 'gray',
                            }),

                        TextColumn::make('disponibilidad')
                            ->color('gray')
                            ->placeholder('No informada')
                            ->visibleFrom('md'),
                    ])->space(1),
                ]),
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
