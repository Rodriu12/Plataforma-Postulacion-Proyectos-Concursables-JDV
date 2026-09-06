<?php

namespace App\Filament\Resources\Organizacions\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;


use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;


use Filament\Tables\Columns\TextColumn;


use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ProyectosRelationManager extends RelationManager
{
    protected static string $relationship = 'proyectos';

    protected static ?string $title = 'Proyectos y Postulaciones';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('titulo')
                    ->label('Título del Proyecto')
                    ->required()
                    ->maxLength(255),
                    
                Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'borrador' => 'Borrador',
                        'en_postulacion' => 'En Postulación',
                        'adjudicado' => 'Adjudicado',
                        'rechazado' => 'Rechazado',
                        'en_ejecucion' => 'En Ejecución',
                        'rendido' => 'Rendido',
                    ])
                    ->required()
                    ->default('borrador'),
                    
                TextInput::make('monto_solicitado')
                    ->label('Monto Solicitado')
                    ->numeric()
                    ->prefix('$'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('titulo')
            ->columns([
                TextColumn::make('titulo')
                    ->label('Proyecto'),
                    
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'borrador' => 'gray',
                        'en_postulacion' => 'warning',
                        'adjudicado' => 'success',
                        'rechazado' => 'danger',
                        'en_ejecucion' => 'info',
                        'rendido' => 'success',
                        default => 'gray',
                    }),
                    
                TextColumn::make('monto_solicitado')
                    ->label('Monto')
                    ->money('CLP'),
            ])
            ->filters([
                // Filtros futuros
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Nuevo Proyecto'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}