<?php

namespace App\Filament\Resources\Organizacions\RelationManagers;

use App\Filament\Resources\Organizacions\OrganizacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ProyectosRelationManager extends RelationManager
{
    protected static string $relationship = 'proyectos';

    protected static ?string $relatedResource = OrganizacionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
