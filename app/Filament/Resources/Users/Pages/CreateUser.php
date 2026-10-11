<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Sin el botón "Crear y crear otro": reenviaba el formulario con los
     * mismos datos y chocaba con las llaves únicas (duplicado).
     */
    protected static bool $canCreateAnother = false;
}
