<?php

namespace App\Filament\Resources\InventarioItems\Pages;

use App\Filament\Resources\InventarioItems\InventarioItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInventarioItem extends EditRecord
{
    protected static string $resource = InventarioItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
