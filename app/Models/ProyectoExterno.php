<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProyectoExterno extends Model
{
    use HasFactory;

    protected $table = 'proyectos_externos';

    protected $fillable = [
        'titulo',
        'descripcion',
        'institucion',
        'url_fuente',
        'fecha_cierre',
        'estado_vigencia',
    ];
}
