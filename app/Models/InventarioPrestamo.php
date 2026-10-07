<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioPrestamo extends Model
{
    use HasFactory;

    protected $table = 'inventario_prestamos';

    protected $fillable = [
        'inventario_item_id',
        'vecino_id',
        'voluntario_id',
        'cantidad',
        'fecha_prestamo',
        'fecha_devolucion_esperada',
        'fecha_devolucion_real',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_prestamo' => 'date',
            'fecha_devolucion_esperada' => 'date',
            'fecha_devolucion_real' => 'date',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventarioItem::class, 'inventario_item_id');
    }

    public function vecino(): BelongsTo
    {
        return $this->belongsTo(Vecino::class);
    }

    public function voluntario(): BelongsTo
    {
        return $this->belongsTo(Voluntario::class);
    }

    protected static function booted(): void
    {
        static::saving(function (InventarioPrestamo $prestamo) {
            if ($prestamo->fecha_devolucion_real && $prestamo->estado === 'prestado') {
                $prestamo->estado = 'devuelto';
            }
        });
    }
}
