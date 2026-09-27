<?php

namespace App\Filament\Resources\Voluntarios\Pages;

use App\Filament\Resources\Voluntarios\VoluntarioResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVoluntarios extends ListRecords
{
    protected static string $resource = VoluntarioResource::class;

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
