<?php

namespace App\Filament\Resources\Organizaciones\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrganizacionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Organización')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('rut_juridico')
                    ->label('RUT Jurídico')
                    ->searchable(),
                    
                TextColumn::make('sector')
                    ->label('Sector')
                    ->searchable(),
                    
                TextColumn::make('fecha_constitucion')
                    ->label('Constitución')
                    ->date(),
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
