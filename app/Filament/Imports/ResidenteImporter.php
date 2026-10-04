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
                ->label('Nombre completo')
                ->exampleHeader('NOMBRE COMPLETO')
                ->example('Juan Pérez Soto')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('email')
                ->label('Correo electrónico')
                ->exampleHeader('CORREO ELECTRÓNICO')
                ->example('juan.perez@gmail.com')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),

            ImportColumn::make('rut')
                ->label('RUT')
                ->exampleHeader('RUT')
                ->example('12.345.678-9')
                ->requiredMapping()
                ->rules(['required', 'max:12']),

            ImportColumn::make('phone')
                ->label('Teléfono')
                ->exampleHeader('TELÉFONO')
                ->example('+56 9 1234 5678')
                ->rules(['nullable', 'max:20']),

            ImportColumn::make('sector')
                ->label('Sector o Villa')
                ->exampleHeader('SECTOR O VILLA')
                ->example('Cerro Parra')
                ->rules(['nullable', 'max:255']),

            ImportColumn::make('role')
                ->label('Rol')
                ->exampleHeader('ROL (VECINO O VOLUNTARIO)')
                ->example('vecino')
                ->rules(['nullable'])
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
        $this->record->is_active = true;
        $this->record->organizacion_id = $this->options['organizacion_id'] ?? null;
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
    }

    protected function afterCreate(): void
    {
        Password::sendResetLink(['email' => $this->record->email]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Se importaron ' . number_format($import->successful_rows) . ' fila(s). Cada persona nueva recibirá un correo para definir su contraseña.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' fila(s) fallaron y puedes revisar el detalle en el archivo descargable.';
        }

        return $body;
    }
}
