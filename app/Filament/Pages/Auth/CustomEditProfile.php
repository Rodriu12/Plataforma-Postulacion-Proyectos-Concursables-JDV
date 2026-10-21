<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class CustomEditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getAvatarFormComponent(),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }

    protected function getAvatarFormComponent(): Component
    {
        return FileUpload::make('avatar_path')
            ->label('Foto de perfil')
            ->avatar()
            ->disk('uploads')
            ->visibility('public')
            ->directory('avatares')
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->rules(['file', 'mimes:jpeg,png,jpg,webp', 'max:2048'])
            ->maxSize(2048)
            ->helperText('Solo formatos JPG, PNG o WEBP (máx. 2 MB). No se permiten GIFs ni audio.')
            ->image()
            ->imageEditor();
    }
}
