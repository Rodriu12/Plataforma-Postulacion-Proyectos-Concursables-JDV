<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organizacion extends Model
{
    use HasFactory;

    // Forzamos el nombre exacto de la tabla en la base de datos
    protected $table = 'organizaciones';

    protected $fillable = [
        'nombre',
        'rut_juridico',
        'sector',
        'fecha_constitucion',
    ];

    // Una Organización tiene muchos Usuarios
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
