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
    public const ROLES_DIRECTIVA = User::ROLES_DIRECTIVA;

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
