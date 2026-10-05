<?php

namespace App\Filament\Resources\Users\Tables;

use App\Support\Avatar;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
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
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->columns([
                Split::make([
                    ImageColumn::make('avatar')
                        ->label('')
                        ->circular()
                        ->size(56)
                        ->getStateUsing(fn ($record) => Avatar::url($record->name))
                        ->grow(false),

                    Stack::make([
                        TextColumn::make('name')
                            ->label('Nombre')
                            ->weight('bold')
                            ->size('lg')
                            ->wrap()
                            ->searchable(),

                        TextColumn::make('email')
                            ->label('Email')
                            ->color('gray')
                            ->wrap()
                            ->searchable(),

                        Stack::make([
                            TextColumn::make('role')
                                ->label('Rol')
                                ->badge()
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

                            IconColumn::make('is_active')
                                ->label('Activo')
                                ->boolean(),
                        ])->space(2),

                        TextColumn::make('rut')
                            ->label('RUT')
                            ->color('gray')
                            ->visibleFrom('md'),

                        TextColumn::make('phone')
                            ->label('Teléfono')
                            ->color('gray')
                            ->visibleFrom('md'),

                        TextColumn::make('created_at')
                            ->dateTime()
                            ->color('gray')
                            ->size('xs')
                            ->toggleable(isToggledHiddenByDefault: true),
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
