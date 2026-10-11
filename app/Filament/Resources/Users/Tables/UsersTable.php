<?php

namespace App\Filament\Resources\Users\Tables;

use App\Support\Tarjetas;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Menos columnas = tarjetas más anchas: una sola en pantallas
            // medianas, dos desde "lg" y tres solo en pantallas muy grandes.
            ->contentGrid(['default' => 1, 'lg' => 2, '2xl' => 3])
            ->columns([
                Stack::make([
                    // Cabecera: foto + nombre/correo, alineada arriba.
                    Split::make([
                        ImageColumn::make('avatar')
                            ->label('')
                            ->circular()
                            ->size(48)
                            ->grow(false)
                            ->getStateUsing(fn ($record) => $record->avatarUrl()),

                        Stack::make([
                            TextColumn::make('name')
                                ->label('Nombre')
                                ->weight('bold')
                                ->size('lg')
                                ->wrap()
                                ->searchable()
                                ->extraAttributes(Tarjetas::TEXTO_QUE_ENVUELVE),

                            TextColumn::make('email')
                                ->label('Email')
                                ->color('gray')
                                ->wrap()
                                ->searchable()
                                ->extraAttributes(Tarjetas::TEXTO_QUE_ENVUELVE),
                        ])->space(1),
                    ])->extraAttributes(['style' => 'align-items:flex-start;']),

                    // Estado de la cuenta, con texto (no solo un ícono).
                    Split::make([
                        TextColumn::make('role')
                            ->label('Rol')
                            ->badge()
                            ->grow(false)
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'admin_central' => 'Admin. Central',
                                'presidente' => 'Presidente/a',
                                'secretario' => 'Secretario/a',
                                'tesorero' => 'Tesorero/a',
                                'director' => 'Director/a',
                                'vecino' => 'Vecino/a',
                                'voluntario' => 'Voluntario/a',
                                default => ucfirst($state),
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'admin_central' => 'primary',
                                'presidente' => 'danger',
                                'secretario' => 'warning',
                                'tesorero' => 'success',
                                'director' => 'info',
                                'voluntario' => 'primary',
                                default => 'gray',
                            }),

                        TextColumn::make('is_active')
                            ->label('Cuenta')
                            ->badge()
                            ->grow(false)
                            ->formatStateUsing(fn ($state) => $state ? 'Activo' : 'Inactivo')
                            ->color(fn ($state) => $state ? 'success' : 'gray'),
                    ])->extraAttributes(['style' => 'flex-wrap:wrap;']),

                    // Detalle con etiqueta propia.
                    Stack::make([
                        Tarjetas::linea('rut', 'RUT'),
                        Tarjetas::linea('phone', 'Teléfono'),
                        Tarjetas::linea('organizacion.nombre', 'Organización'),
                    ])->space(1),
                ])->space(3),
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
