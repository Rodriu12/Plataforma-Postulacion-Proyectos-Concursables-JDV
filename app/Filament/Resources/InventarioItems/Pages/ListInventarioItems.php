<?php

namespace App\Filament\Resources\InventarioItems\Pages;

use App\Filament\Resources\InventarioItems\InventarioItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventarioItems extends ListRecords
{
    protected static string $resource = InventarioItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
