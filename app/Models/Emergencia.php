<?php

namespace App\Models;

use App\Mail\NuevaEmergenciaMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;

class Emergencia extends Model
{
    use HasFactory;

    /**
     * Roles que forman parte de la directiva y deben ser notificados
     * cuando se reporta una nueva emergencia.
     */
    public const ROLES_DIRECTIVA = ['presidente', 'secretario', 'tesorero', 'director'];

    protected $fillable = [
        'vecino_id',
        'organizacion_id',
        'tipo',
        'descripcion',
        'ubicacion',
        'evidencia',
        'estado',
    ];

    public function vecino(): BelongsTo
    {
        return $this->belongsTo(Vecino::class);
    }

    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class);
    }

    protected static function booted()
    {
        static::created(function (Emergencia $emergencia) {
            $emergencia->notificarADirectiva();
        });
    }

    /**
     * Envía un correo a la directiva (de la organización asociada si existe,
     * o a toda la directiva registrada si la emergencia no tiene organización).
     */
    public function notificarADirectiva(): void
    {
        $directiva = User::whereIn('role', self::ROLES_DIRECTIVA)
            ->when($this->organizacion_id, fn ($query) => $query->where('organizacion_id', $this->organizacion_id))
            ->get();

        if ($directiva->isEmpty()) {
            $directiva = User::whereIn('role', self::ROLES_DIRECTIVA)->get();
        }

        foreach ($directiva as $usuario) {
            Mail::to($usuario->email)->send(new NuevaEmergenciaMail($this));
        }
    }
}
