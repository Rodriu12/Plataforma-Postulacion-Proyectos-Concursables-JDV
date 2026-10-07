<?php

namespace App\Filament\Resources\Reuniones\Pages;

use App\Filament\Resources\Reuniones\ReunionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReunion extends CreateRecord
{
    protected static string $resource = ReunionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
