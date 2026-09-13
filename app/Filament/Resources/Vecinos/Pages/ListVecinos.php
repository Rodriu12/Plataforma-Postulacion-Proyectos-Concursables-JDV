<?php

namespace App\Filament\Resources\Vecinos\Pages;

use App\Filament\Resources\Vecinos\VecinoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVecinos extends ListRecords
{
    protected static string $resource = VecinoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
