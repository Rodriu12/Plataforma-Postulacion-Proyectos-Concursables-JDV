<?php

namespace App\Filament\Resources\Organizaciones\Pages;

use App\Filament\Resources\Organizaciones\OrganizacionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrganizacion extends EditRecord
{
    protected static string $resource = OrganizacionResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [ViewAction::make()];

        if (auth()->user()?->esAdminCentral()) {
            $actions[] = DeleteAction::make();
        }

        return $actions;
    }
}
