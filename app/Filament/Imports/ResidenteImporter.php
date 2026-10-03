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

    /**
     * El presidente/secretario sube un Excel con sus vecinos y voluntarios;
     * esta clase define cómo se lee cada fila y se crea/actualiza cada User.
     * No se pide contraseña en la planilla: se genera una aleatoria que
     * nadie conoce y se le envía un correo de "definir tu contraseña".
     */
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

    /**
     * Busca por email: si ya existe, actualiza sus datos; si no, lo crea.
     * Así se puede volver a subir la misma planilla para corregir datos
     * sin generar usuarios duplicados.
     */
    public function resolveRecord(): ?User
    {
        return User::firstOrNew([
            'email' => $this->data['email'],
        ]);
    }

    protected function beforeCreate(): void
    {
        // Contraseña aleatoria que nadie llega a conocer: el residente la
        // define él mismo a través del correo de restablecimiento.
        $this->record->password = Str::password(24);
        $this->record->is_active = true;

        // La organización nunca se escribe en el Excel: siempre es la del
        // presidente/secretario que sube la planilla (viene de las options
        // del ImportAction, evaluadas en el momento de iniciar la carga).
        $this->record->organizacion_id = $this->options['organizacion_id'] ?? null;
    }

    /**
     * Corre tanto para filas nuevas como actualizadas. Para entonces el
     * User ya se guardó, por lo que su Vecino/Voluntario ya existe (se
     * autocrea en User::booted()). Completamos sector/teléfono con lo que
     * trae el Excel y marcamos el registro como aprobado, ya que el propio
     * presidente está dando fe de estos datos al subir la planilla.
     */
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
