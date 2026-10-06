<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Notifications\Notification as FilamentNotification;

class Vecino extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
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

    protected $fillable = [
        'user_id',
        'nombre',
        'rut',
        'direccion',
        'sector',
        'telefono',
        'comprobante_domicilio',
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
