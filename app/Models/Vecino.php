<?php

namespace App\Models;

use App\Services\DocumentoOcrService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\Storage;

class Vecino extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (Vecino $vecino) {
            if (blank($vecino->comprobante_domicilio)) {
                $vecino->comprobante_hash = null;
                $vecino->comprobante_ocr_text = null;
                $vecino->comprobante_validado = null;
                $vecino->comprobante_validado_at = null;
                $vecino->comprobante_motivo = null;

                return;
            }

            $rutaPdf = Storage::disk('uploads')->path($vecino->comprobante_domicilio);
            if (! is_file($rutaPdf)) {
                $vecino->comprobante_hash = null;
                $vecino->comprobante_ocr_text = null;
                $vecino->comprobante_validado = false;
                $vecino->comprobante_validado_at = null;
                $vecino->comprobante_motivo = 'No se pudo encontrar el archivo PDF subido.';

                return;
            }

            $vecino->comprobante_hash = hash_file('sha256', $rutaPdf);

            $resultado = $vecino->validarComprobante();

            $vecino->comprobante_ocr_text = $resultado['texto'];
            $vecino->comprobante_validado = $resultado['aprobado'];
            $vecino->comprobante_validado_at = $resultado['aprobado'] === true ? now() : null;
            $vecino->comprobante_motivo = $resultado['motivo'];
        });

        static::updated(function (Vecino $vecino) {
            if (! $vecino->wasChanged('estado') || ! $vecino->user) {
                return;
            }

            if (! in_array($vecino->estado, ['aprobado', 'rechazado'])) {
                return;
            }

            FilamentNotification::make()
                ->title($vecino->estado === 'aprobado' ? 'Tu registro como vecino fue aprobado' : 'Tu registro como vecino fue rechazado')
                ->icon('heroicon-o-identification')
                ->color($vecino->estado === 'aprobado' ? 'success' : 'danger')
                ->sendToDatabase($vecino->user);
        });
    }

    public function validarComprobante(): array
    {
        if (blank($this->comprobante_domicilio)) {
            return [
                'aprobado' => false,
                'texto' => '',
                'motivo' => 'No se ha cargado un comprobante de domicilio.',
            ];
        }

        $rutaPdf = Storage::disk('uploads')->path($this->comprobante_domicilio);

        if (! is_file($rutaPdf)) {
            return [
                'aprobado' => false,
                'texto' => '',
                'motivo' => 'No se pudo encontrar el comprobante PDF subido.',
            ];
        }

        return app(DocumentoOcrService::class)->validarDocumento($rutaPdf, [
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'sector' => $this->sector,
        ]);
    }

    protected $casts = [
        'comprobante_validado' => 'boolean',
        'comprobante_validado_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'nombre',
        'rut',
        'direccion',
        'sector',
        'telefono',
        'comprobante_domicilio',
        'comprobante_hash',
        'comprobante_ocr_text',
        'comprobante_validado',
        'comprobante_validado_at',
        'comprobante_motivo',
        'estado',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emergencias()
    {
        return $this->hasMany(Emergencia::class);
    }

    public function prestamosInventario()
    {
        return $this->hasMany(InventarioPrestamo::class);
    }
}
