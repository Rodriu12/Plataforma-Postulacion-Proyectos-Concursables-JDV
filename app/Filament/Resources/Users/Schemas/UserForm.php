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
                    ->disk('uploads')
                    ->visibility('public')
                    ->directory('avatares')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->rules(['file', 'mimes:jpeg,png,jpg,webp', 'max:2048'])
                    ->maxSize(2048)
                    ->helperText('Solo formatos JPG, PNG o WEBP (máx. 2 MB). No se permiten GIFs ni audio.')
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
                    ->extraInputAttributes(['x-on:input' => <<<'JS'
                            let raw = $el.value.replace(/[^0-9kK]/g, '');

                            if (! raw) {
                                $el.value = '';
                                return;
                            }

                            let dv = raw.slice(-1).toUpperCase();
                            let cuerpo = raw.slice(0, -1);

                            if (! cuerpo) {
                                $el.value = dv;
                                return;
                            }

                            $el.value = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '-' + dv;
                        JS]),
                TextInput::make('phone')
                    ->label('Telefono')
                    ->tel()
                    ->required()
                    ->maxLength(20)
                    ->placeholder('+56 9 1234 5678')
                    ->rules([
                        'required',
                        'regex:/^(?:\+56\s?)?(?:9[\s-]?\d{4}[\s-]?\d{4}|[2-9][\s-]?\d{3,4}[\s-]?\d{4})$/',
                    ])
                    ->extraInputAttributes(['x-on:input' => <<<'JS'
                            let digits = $el.value.replace(/\D/g, '');

                            if (! digits) {
                                $el.value = '';
                                return;
                            }

                            if (digits.startsWith('56')) {
                                digits = digits.slice(2);
                            }

                            if (digits.length > 9) {
                                digits = digits.slice(0, 9);
                            }

                            let formatted = digits;

                            if (digits.startsWith('9')) {
                                if (digits.length <= 1) {
                                    formatted = digits;
                                } elseif (digits.length <= 5) {
                                    formatted = digits.replace(/(\d{1})(\d{0,4})/, '$1 $2').trim();
                                } else {
                                    formatted = digits.replace(/(\d{1})(\d{4})(\d{0,4})/, '$1 $2 $3').trim();
                                }
                            } else {
                                if (digits.length <= 4) {
                                    formatted = digits;
                                } else {
                                    formatted = digits.replace(/(\d{4})(\d{0,4})/, '$1 $2').trim();
                                }
                            }

                            $el.value = '+56 ' + formatted.replace(/\s+/g, ' ').trim();
                        JS]),
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
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),
            ]);
    }
}
