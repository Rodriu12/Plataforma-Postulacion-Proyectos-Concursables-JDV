<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioMovimiento extends Model
{
    use HasFactory;

    protected $table = 'inventario_movimientos';

    protected $fillable = [
        'inventario_item_id',
        'user_id',
        'tipo',
        'cantidad',
        'motivo',
        'observaciones',
        'fecha',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventarioItem::class, 'inventario_item_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted(): void
    {
        static::creating(function (InventarioMovimiento $movimiento) {
            $movimiento->user_id ??= auth()->id();
            $movimiento->fecha ??= now();
        });

        static::created(function (InventarioMovimiento $movimiento) {
            $delta = $movimiento->tipo === 'salida' ? -$movimiento->cantidad : $movimiento->cantidad;

            $movimiento->item()->increment('cantidad_total', $delta);
        });

        static::deleted(function (InventarioMovimiento $movimiento) {
            $delta = $movimiento->tipo === 'salida' ? $movimiento->cantidad : -$movimiento->cantidad;

            $movimiento->item()->increment('cantidad_total', $delta);
        });
    }
}
