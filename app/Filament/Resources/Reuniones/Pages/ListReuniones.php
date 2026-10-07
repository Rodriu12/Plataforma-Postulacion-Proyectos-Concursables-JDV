<?php

namespace App\Filament\Resources\Reuniones\Pages;

use App\Filament\Resources\Reuniones\ReunionResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReuniones extends ListRecords
{
    protected static string $resource = ReunionResource::class;

    protected function getHeaderActions(): array
    {
        $usuario = auth()->user();

        if (! $usuario || ! in_array($usuario->role, User::ROLES_DIRECTIVA)) {
            return [];
        }

        return [
            CreateAction::make()
                ->createAnother(false),
        ];
    }
}
