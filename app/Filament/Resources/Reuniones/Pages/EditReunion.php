<?php

namespace App\Filament\Resources\Reuniones\Pages;

use App\Filament\Resources\Reuniones\ReunionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReunion extends EditRecord
{
    protected static string $resource = ReunionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
