<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventarioItem extends Model
{
    use HasFactory;

    protected $table = 'inventario_items';

    protected $fillable = [
        'organizacion_id',
        'proyecto_id',
        'nombre',
        'categoria',
        'descripcion',
        'unidad',
        'cantidad_total',
        'ubicacion',
        'estado',
    ];

    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(InventarioMovimiento::class);
    }

    public function prestamos(): HasMany
    {
        return $this->hasMany(InventarioPrestamo::class);
    }

    public function getCantidadPrestadaAttribute(): int
    {
        return $this->prestamos()
            ->whereIn('estado', ['prestado', 'atrasado'])
            ->sum('cantidad');
    }

    public function getCantidadDisponibleAttribute(): int
    {
        return max(0, $this->cantidad_total - $this->cantidad_prestada);
    }
}
