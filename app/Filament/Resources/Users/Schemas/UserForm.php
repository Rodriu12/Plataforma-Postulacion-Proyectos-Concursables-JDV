<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    protected static function esAdminCentral(): bool
    {
        return auth()->user()?->esAdminCentral() ?? false;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar_path')
                    ->label('Foto de perfil')
                    ->avatar()
                    ->disk('public')
                    ->directory('avatares')
                    ->image()
                    ->columnSpanFull(),
                Select::make('organizacion_id')
                    ->label('Organización')
                    ->relationship('organizacion', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(fn () => auth()->user()?->organizacion_id)
                    ->disabled(fn () => ! static::esAdminCentral())
                    ->dehydrated()
                    ->helperText(fn () => static::esAdminCentral()
                        ? 'Elige a qué organización pertenecerá esta cuenta.'
                        : 'Se asigna automáticamente a tu organización.'),
                TextInput::make('name')
                    ->label('Nombre Completo')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Correo electrónico o e-mail')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('rut')
                    ->label('RUT')
                    ->required()
                    ->maxLength(12)
                    ->extraAlpineAttributes(['x-on:input' => <<<'JS'
                            let raw = $el.value.replace(/[^0-9kK]/g, '');
                            if (raw.length > 1) {
                                let cuerpo = raw.slice(0, -1).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                let dv = raw.slice(-1).toUpperCase();
                                $el.value = cuerpo + '-' + dv;
                            } else {
                                $el.value = raw.toUpperCase();
                            }
                        JS]),
                TextInput::make('phone')
                    ->label('Telefono')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                Select::make('role')
                    ->label('Seleccione su rol asignado dentro de su organización')
                    ->options(fn () => array_merge([
                        'presidente' => 'Presidente/a',
                        'secretario' => 'Secretario/a',
                        'tesorero'   => 'Tesorero/a',
                        'director'   => 'Director/a',
                        'vecino'     => 'Vecino/a',
                        'voluntario' => 'Voluntario/a',
                    ], static::esAdminCentral() ? ['admin_central' => 'Administrador/a Central'] : []))
                    ->required()
                    ->default('vecino'),
                Toggle::make('is_active')
                    ->label('Cuenta Activa')
                    ->default(true)
                    ->columnSpanFull(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),
            ]);
    }
}
