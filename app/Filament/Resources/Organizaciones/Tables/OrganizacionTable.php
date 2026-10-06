<?php

namespace App\Filament\Resources\Organizaciones\Tables;

use App\Support\Avatar;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrganizacionTable
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
                        ->getStateUsing(fn ($record) => Avatar::url($record->nombre, '1d4ed8'))
                        ->grow(false),

                    Stack::make([
                        TextColumn::make('nombre')
                            ->label('Organización')
                            ->weight('bold')
                            ->size('lg')
                            ->wrap()
                            ->searchable(),

                        TextColumn::make('sector')
                            ->color('gray')
                            ->wrap()
                            ->searchable(),

                        TextColumn::make('rut_juridico')
                            ->label('RUT Jurídico')
                            ->color('gray')
                            ->searchable()
                            ->visibleFrom('md'),

                        TextColumn::make('fecha_constitucion')
                            ->label('Constitución')
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
