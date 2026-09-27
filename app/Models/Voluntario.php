<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voluntario extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organizacion_id',
        'nombre',
        'telefono',
        'sector',
        'area_apoyo',
        'disponibilidad',
        'estado',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function emergencias(): HasMany
    {
        return $this->hasMany(Emergencia::class);
    }
}
