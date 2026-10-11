<?php

namespace App\Filament\Resources\Vecinos\Tables;

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

class VecinosTable
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
                            ->getStateUsing(fn ($record) => $record->user?->avatarUrl() ?? Avatar::url($record->nombre, '16a34a')),

                        Stack::make([
                            TextColumn::make('nombre')
                                ->weight('bold')
                                ->size('lg')
                                ->wrap()
                                ->searchable()
                                ->extraAttributes(Tarjetas::TEXTO_QUE_ENVUELVE),

                            TextColumn::make('user.email')
                                ->label('Correo')
                                ->color('gray')
                                ->wrap()
                                ->searchable()
                                ->placeholder('Sin cuenta vinculada')
                                ->extraAttributes(Tarjetas::TEXTO_QUE_ENVUELVE),
                        ])->space(1),
                    ])->extraAttributes(['style' => 'align-items:flex-start;']),

                    Split::make([
                        TextColumn::make('estado')
                            ->label('Estado')
                            ->badge()
                            ->grow(false)
                            ->color(fn (string $state): string => match ($state) {
                                'pendiente' => 'warning',
                                'aprobado' => 'success',
                                'rechazado' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state) => ucfirst($state)),
                    ]),

                    Stack::make([
                        Tarjetas::linea('rut', 'RUT'),
                        Tarjetas::linea('direccion', 'Dirección'),
                        Tarjetas::linea('sector', 'Sector'),

                        TextColumn::make('certificado')
                            ->label('Certificado')
                            ->getStateUsing(fn ($record) => $record?->estado === 'aprobado' ? 'Descargar certificado de residencia' : 'Certificado no disponible')
                            ->color(fn ($record) => $record?->estado === 'aprobado' ? 'primary' : 'gray')
                            ->weight('bold')
                            ->wrap()
                            ->url(fn ($record) => $record?->estado === 'aprobado' ? route('vecino.certificado', $record->id) : null)
                            ->openUrlInNewTab(),
                    ])->space(1),
                ])->space(3),
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
