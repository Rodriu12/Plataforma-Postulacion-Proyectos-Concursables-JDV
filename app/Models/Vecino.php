<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vecino extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'rut',
        'direccion',
        'sector',
        'telefono',
        'comprobante_domicilio',
        'estado',
    ];
}
