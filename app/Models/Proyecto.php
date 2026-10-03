<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proyecto extends Model
{
    use HasFactory;
    protected $table = 'proyectos';

    protected $fillable = [
        'organizacion_id',
        'titulo',
        'descripcion',
        'fuente_financiamiento',
        'monto_solicitado',
        'monto_adjudicado',
        'estado',
        'fecha_postulacion',
        'fecha_adjudicacion',
    ];


    protected function estado(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value !== null ? strtolower(trim($value)) : $value,
        );
    }

    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class);
    }
}
