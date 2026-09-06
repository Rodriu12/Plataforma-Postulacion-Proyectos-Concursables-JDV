<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organizacion extends Model
{
    use HasFactory;
    protected $table = 'organizaciones';

    protected $fillable = [
        'nombre',
        'rut_juridico',
        'sector',
        'fecha_constitucion',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function proyectos(): HasMany
    {
        return $this->hasMany(Proyecto::class);
    }
}
