<?php

namespace App\Filament\Resources\Vecinos\Tables;

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

class VecinosTable
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
                        ->getStateUsing(fn ($record) => $record->user?->avatarUrl() ?? Avatar::url($record->nombre, '16a34a'))
                        ->grow(false),

                    Stack::make([
                        TextColumn::make('nombre')
                            ->weight('bold')
                            ->size('lg')
                            ->wrap()
                            ->searchable(),

                        TextColumn::make('user.email')
                            ->label('Correo')
                            ->color('gray')
                            ->wrap()
                            ->searchable()
                            ->placeholder('Sin cuenta vinculada'),

                        TextColumn::make('estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pendiente' => 'warning',
                                'aprobado' => 'success',
                                'rechazado' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state) => ucfirst($state)),

                        TextColumn::make('direccion')
                            ->color('gray')
                            ->wrap()
                            ->visibleFrom('md'),

                        TextColumn::make('rut')
                            ->color('gray')
                            ->size('xs')
                            ->visibleFrom('md'),

                        TextColumn::make('certificado')
                            ->label('Certificado')
                            ->getStateUsing(fn ($record) => $record?->estado === 'aprobado' ? 'Descargar' : 'No disponible')
                            ->color(fn ($record) => $record?->estado === 'aprobado' ? 'primary' : 'gray')
                            ->weight('bold')
                            ->url(fn ($record) => $record?->estado === 'aprobado' ? route('vecino.certificado', $record->id) : null)
                            ->openUrlInNewTab(),
                    ])->space(1),
                ]),
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
