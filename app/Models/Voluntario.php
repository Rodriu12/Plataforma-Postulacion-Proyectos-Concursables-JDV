<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Notifications\Notification as FilamentNotification;

class Voluntario extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::updated(function (Voluntario $voluntario) {
            if (! $voluntario->wasChanged('estado') || ! $voluntario->user) {
                return;
            }

            if (! in_array($voluntario->estado, ['aprobado', 'rechazado'])) {
                return;
            }

            FilamentNotification::make()
                ->title($voluntario->estado === 'aprobado' ? 'Tu registro como voluntario fue aprobado' : 'Tu registro como voluntario fue rechazado')
                ->icon('heroicon-o-hand-raised')
                ->color($voluntario->estado === 'aprobado' ? 'success' : 'danger')
                ->sendToDatabase($voluntario->user);
        });
    }

    protected $fillable = [
        'user_id',
        'organizacion_id',
        'nombre',
        'telefono',
        'sector',
        'area_apoyo',
        'disponibilidad',
        'estado',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function emergencias(): HasMany
    {
        return $this->hasMany(Emergencia::class);
    }

    public function prestamosInventario(): HasMany
    {
        return $this->hasMany(InventarioPrestamo::class);
    }
}
