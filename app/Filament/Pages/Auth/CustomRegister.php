<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class CustomRegister extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getRutFormComponent(),
                $this->getPhoneFormComponent(),
                $this->getEmailFormComponent(),
                $this->getRoleFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getRutFormComponent(): Component
    {
        return TextInput::make('rut')
            ->label('RUT')
            ->required()
            ->unique($this->getUserModel())
            ->maxLength(12);
    }

    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label('Teléfono')
            ->tel()
            ->maxLength(15);
    }

    /**
     * Registro público: solo se puede uno registrar como vecino o voluntario.
     * Los roles de directiva (presidente, secretario, tesorero, director) se
     * asignan desde el panel de Usuarios, nunca por autoregistro.
     */
    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->label('Quiero registrarme como')
            ->options([
                'vecino' => 'Vecino/a',
                'voluntario' => 'Voluntario/a',
            ])
            ->required()
            ->default('vecino');
    }

    /**
     * Defensa extra: aunque alguien manipule el formulario en el navegador,
     * el rol guardado nunca puede ser distinto de vecino/voluntario, y la
     * cuenta queda activa por defecto.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['role'] = in_array($data['role'] ?? null, ['vecino', 'voluntario'], true)
            ? $data['role']
            : 'vecino';

        $data['is_active'] = true;

        return $data;
    }
}
