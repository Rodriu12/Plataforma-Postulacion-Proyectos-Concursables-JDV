<?php

namespace App\Models;

use App\Notifications\VecinoComprobanteRevisionNotification;
use App\Notifications\VecinoEstadoNotification;
use App\Services\DocumentoOcrService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
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

        static::saved(function (Vecino $vecino) {
            if ($vecino->comprobante_validado !== false) {
                return;
            }

            $debeNotificar = $vecino->wasRecentlyCreated
                || $vecino->wasChanged('comprobante_validado')
                || $vecino->wasChanged('comprobante_domicilio')
                || $vecino->wasChanged('direccion')
                || $vecino->wasChanged('sector')
                || $vecino->wasChanged('nombre');

            if (! $debeNotificar) {
                return;
            }

            $organizacionId = $vecino->user?->organizacion_id;

            $query = User::query()
                ->where(function ($q) use ($organizacionId) {
                    $q->where(function ($subQuery) use ($organizacionId) {
                        $subQuery->whereIn('role', ['presidente', 'secretario']);

                        if (filled($organizacionId)) {
                            $subQuery->where('organizacion_id', $organizacionId);
                        }
                    });

                    $q->orWhere('role', User::ROLE_ADMIN_CENTRAL);
                });

            if ($vecino->user_id && $vecino->user?->role === 'vecino') {
                $query->whereKeyNot($vecino->user_id);
            }

            $destinatarios = $query->get();

            if ($destinatarios->isEmpty()) {
                $destinatarios = User::query()->where('role', User::ROLE_ADMIN_CENTRAL)->get();
            }

            if ($destinatarios->isEmpty() && auth()->check()) {
                $destinatarios = collect([auth()->user()]);
            }

            if ($destinatarios->isNotEmpty()) {
                Notification::send($destinatarios, new VecinoComprobanteRevisionNotification($vecino));

                foreach ($destinatarios as $destinatario) {
                    \Filament\Notifications\Events\DatabaseNotificationsSent::dispatch($destinatario);
                }
            } else {
                Log::warning('No se encontraron destinatarios para la notificación OCR del vecino.', [
                    'vecino_id' => $vecino->id,
                    'organizacion_id' => $organizacionId,
                    'user_id' => $vecino->user?->id,
                ]);
            }
        });

        static::updated(function (Vecino $vecino) {
            if ($vecino->user && $vecino->wasChanged('estado') && in_array($vecino->estado, ['aprobado', 'rechazado'])) {
                Notification::send($vecino->user, new VecinoEstadoNotification($vecino));
                \Filament\Notifications\Events\DatabaseNotificationsSent::dispatch($vecino->user);
            }
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
