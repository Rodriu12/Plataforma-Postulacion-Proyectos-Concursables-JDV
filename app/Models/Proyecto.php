<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proyecto extends Model
{
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
    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class);
    }
}
