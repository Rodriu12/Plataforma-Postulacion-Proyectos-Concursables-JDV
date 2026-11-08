<?php

namespace App\Filament\Resources\Organizaciones\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
class OrganizacionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre de la Junta de Vecinos')
                    ->required()
                    ->maxLength(255),

                TextInput::make('rut_juridico')
                    ->label('RUT Jurídico')
                    ->maxLength(12)
                    ->extraInputAttributes([
                        'x-on:input' => <<<'JS'
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
                        JS
                    ]),

                TextInput::make('sector')
                    ->label('Sector / Localidad')
                    ->placeholder('Ej: Sector Cerro Parra')
                    ->maxLength(100),

                DatePicker::make('fecha_constitucion')
                    ->label('Fecha de Constitución'),
            ]);
    }
}
