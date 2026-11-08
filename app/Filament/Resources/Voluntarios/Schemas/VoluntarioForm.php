<?php

namespace App\Filament\Resources\Voluntarios\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VoluntarioForm
{
    protected static function esDirectiva(): bool
    {
        $usuario = auth()->user();

        return $usuario && in_array($usuario->role, User::ROLES_DIRECTIVA);
    }

    protected static function esAdminCentral(): bool
    {
        return auth()->user()?->esAdminCentral() ?? false;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Usuario de la organización')
                    ->options(function () {
                        $usuarioActual = auth()->user();

                        $query = User::query()
                            ->where('is_active', true)
                            ->whereNotNull('name');

                        if ($usuarioActual?->esAdminCentral()) {
                            $query->orderBy('name');
                        } elseif (! empty($usuarioActual?->organizacion_id)) {
                            $query->where('organizacion_id', $usuarioActual->organizacion_id);
                            $query->orderBy('name');
                        } else {
                            $query->where('id', $usuarioActual?->id ?? 0);
                        }

                        return $query->get()->mapWithKeys(fn (User $usuarioSeleccionado) => [
                            $usuarioSeleccionado->id => $usuarioSeleccionado->name . ($usuarioSeleccionado->rut ? ' — ' . $usuarioSeleccionado->rut : ''),
                        ])->all();
                    })
                    ->searchable()
                    ->preload()
                    ->placeholder('Selecciona un usuario')
                    ->helperText('Solo aparecen los usuarios activos de la organización actual; si eres administrador central, se muestran todos los usuarios activos.')
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if (empty($state)) {
                            return;
                        }

                        $usuario = User::with('vecino')->find($state);

                        $set('nombre', $usuario?->name ?? '');
                        $set('telefono', $usuario?->phone ?? '');
                        $set('sector', $usuario?->vecino?->sector ?? 'Por definir');
                        $set('organizacion_id', $usuario?->organizacion_id ?? auth()->user()?->organizacion_id);
                    })
                    ->required(),

                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->readOnly(),

                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(20)
                    ->placeholder('+56 9 1234 5678')
                    ->rules([
                        'nullable',
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
                        JS])
                    ->readOnly(),

                Select::make('organizacion_id')
                    ->label('Junta de Vecinos / Organización a la que apoya')
                    ->relationship('organizacion', 'nombre')
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->user()?->organizacion_id)
                    ->disabled(fn () => ! static::esAdminCentral())
                    ->dehydrated(),

                TextInput::make('sector')
                    ->label('Sector o Villa')
                    ->placeholder('Ej: Cerro Parra, Los Copihues...')
                    ->maxLength(255)
                    ->readOnly(),

                Select::make('area_apoyo')
                    ->label('Área de apoyo')
                    ->options([
                        'rescate' => 'Rescate',
                        'logistica' => 'Logística',
                        'primeros_auxilios' => 'Primeros auxilios',
                        'comunicaciones' => 'Comunicaciones',
                        'otro' => 'Otro',
                    ])
                    ->required(),

                TextInput::make('disponibilidad')
                    ->label('Disponibilidad (días / horario)')
                    ->placeholder('Ej: Fines de semana, tardes')
                    ->maxLength(255),

                Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente de revisión',
                        'aprobado' => 'Aprobado',
                        'rechazado' => 'Rechazado',
                    ])
                    ->default('pendiente')
                    ->disabled(fn () => ! static::esDirectiva())
                    ->dehydrated()
                    ->required(),
            ]);
    }
}
