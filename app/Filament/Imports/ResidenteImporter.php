<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResidenteImporter extends Importer
{
    protected static ?string $model = User::class;
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Nombre Completo')
                ->exampleHeader('NOMBRE COMPLETO')
                ->example('Juan Pérez Soto')
                ->requiredMapping()
                ->rules([
                    'required',
                    'string',
                    'max:255',
                    function (string $attribute, mixed $value, \Closure $fail): void {
                        if (! is_string($value) || ! preg_match('/^[\p{L}\s\.\'\-]+$/u', trim($value))) {
                            $fail('El nombre solo debe contener letras y espacios en español, sin números ni símbolos extraños.');
                        }
                    },
                ]),

            ImportColumn::make('email')
                ->label('Correo Electrónico')
                ->exampleHeader('CORREO ELECTRÓNICO')
                ->example('juan.perez@gmail.com')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),

            ImportColumn::make('rut')
                ->label('RUT')
                ->exampleHeader('RUT')
                ->example('12.345.678-9')
                ->requiredMapping()
                ->rules([
                    'required',
                    'max:12',
                    function (string $attribute, mixed $value, \Closure $fail): void {
                        $limpio = preg_replace('/[\.\s]/', '', (string) $value);
                        if (! preg_match('/^\d{7,8}-[\dkK]$/i', $limpio)) {
                            $fail('El RUT debe tener un formato válido (ej: 12.345.678-9 o 12345678-9).');
                        }
                    },
                ])
                ->castStateUsing(function (?string $state): ?string {
                    if (! $state) {
                        return null;
                    }
                    return strtoupper(trim(preg_replace('/\s+/', '', $state)));
                }),

            ImportColumn::make('phone')
                ->label('Teléfono')
                ->exampleHeader('TELÉFONO')
                ->example('+56 9 1234 5678')
                ->rules([
                    'nullable',
                    'max:20',
                    function (string $attribute, mixed $value, \Closure $fail): void {
                        if (filled($value) && ! preg_match('/^[\+0-9\s\-]+$/', (string) $value)) {
                            $fail('El teléfono solo puede contener números, espacios, guiones y el signo +.');
                        }
                    },
                ]),

            ImportColumn::make('sector')
                ->label('Sector o Villa')
                ->exampleHeader('SECTOR O VILLA')
                ->example('Cerro Parra')
                ->rules([
                    'nullable',
                    'max:255',
                    function (string $attribute, mixed $value, \Closure $fail): void {
                        if (filled($value) && ! preg_match('/^[\p{L}0-9\s\.\,\#\-\/]+$/u', (string) $value)) {
                            $fail('El sector o villa contiene caracteres no permitidos.');
                        }
                    },
                ])
                ->fillRecordUsing(fn () => null),

            ImportColumn::make('role')
                ->label('Rol')
                ->exampleHeader('ROL')
                ->example('vecino')
                ->guess(['rol', 'rol (vecino o voluntario)', 'rol vecino o voluntario', 'tipo'])
                ->helperText('vecino o voluntario')
                ->rules([
                    'nullable',
                    function (string $attribute, mixed $value, \Closure $fail): void {
                        if (filled($value)) {
                            $val = strtolower(trim((string) $value));
                            if (! in_array($val, ['vecino', 'voluntario'], true)) {
                                $fail('El rol debe ser "vecino" o "voluntario".');
                            }
                        }
                    },
                ])
                ->castStateUsing(function (?string $state): string {
                    $valor = strtolower(trim((string) $state));

                    return in_array($valor, ['vecino', 'voluntario'], true) ? $valor : 'vecino';
                }),
        ];
    }
    public function resolveRecord(): ?User
    {
        return User::firstOrNew([
            'email' => $this->data['email'],
        ]);
    }

    protected function beforeCreate(): void
    {
        $this->record->password = Str::password(24);

        $usuarioActual = auth()->user();
        $organizacionId = null;

        if ($usuarioActual && ! $usuarioActual->esAdminCentral()) {
            $organizacionId = $usuarioActual->organizacion_id;
        } elseif (! empty($this->options['organizacion_id'])) {
            $organizacionId = (int) $this->options['organizacion_id'];
        }

        $this->record->organizacion_id = $organizacionId;
        $this->record->is_active = filled($organizacionId);
    }
    protected function afterSave(): void
    {
        $sector = trim((string) ($this->data['sector'] ?? '')) ?: 'Por definir';

        $perfil = $this->record->role === 'voluntario'
            ? $this->record->voluntario
            : $this->record->vecino;

        $perfil?->update([
            'sector' => $sector,
            'telefono' => $this->record->phone,
            'estado' => 'aprobado',
        ]);

        if ($this->record->role === 'voluntario' && $this->record->voluntario && filled($this->record->organizacion_id)) {
            $this->record->voluntario->update([
                'organizacion_id' => $this->record->organizacion_id,
            ]);
        }
    }

    protected function afterCreate(): void
    {
        if (filled($this->record->organizacion_id)) {
            Password::sendResetLink(['email' => $this->record->email]);
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Se importaron ' . number_format($import->successful_rows) . ' fila(s). Las personas con organización asignada recibirán un correo para definir su contraseña.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' fila(s) fallaron y puedes revisar el detalle en el archivo descargable.';
        }

        return $body;
    }
}
