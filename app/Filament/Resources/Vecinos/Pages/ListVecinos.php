<?php

namespace App\Filament\Resources\Vecinos\Pages;

use App\Filament\Resources\Vecinos\VecinoResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVecinos extends ListRecords
{
    protected static string $resource = VecinoResource::class;

    protected function getHeaderActions(): array
    {
        $usuario = auth()->user();

        if (! $usuario || ! in_array($usuario->role, User::ROLES_DIRECTIVA)) {
            return [];
        }

        return [
            CreateAction::make(),
        ];
    }
}
