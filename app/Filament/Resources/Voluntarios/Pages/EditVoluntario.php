<?php

namespace App\Filament\Resources\Voluntarios\Pages;

use App\Filament\Resources\Voluntarios\VoluntarioResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVoluntario extends EditRecord
{
    protected static string $resource = VoluntarioResource::class;

    protected function getHeaderActions(): array
    {
        $usuario = auth()->user();

        if (! $usuario || ! in_array($usuario->role, User::ROLES_DIRECTIVA)) {
            return [];
        }

        return [
            DeleteAction::make(),
        ];
    }
}
