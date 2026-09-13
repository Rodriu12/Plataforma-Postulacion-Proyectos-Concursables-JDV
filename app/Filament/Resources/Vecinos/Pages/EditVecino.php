<?php

namespace App\Filament\Resources\Vecinos\Pages;

use App\Filament\Resources\Vecinos\VecinoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVecino extends EditRecord
{
    protected static string $resource = VecinoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
