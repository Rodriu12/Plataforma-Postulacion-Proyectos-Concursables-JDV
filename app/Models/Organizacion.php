<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function rutJuridico(): Attribute
    {
        return Attribute::make(
            set: function (?string $value): ?string {
                if (blank($value)) {
                    return null;
                }

                $limpio = preg_replace('/[^0-9kK]/', '', (string) $value);

                if ($limpio === '') {
                    return null;
                }

                $dv = strtoupper(substr($limpio, -1));
                $cuerpo = substr($limpio, 0, -1);

                if ($cuerpo === '') {
                    return $dv;
                }

                return preg_replace('/(?<=\d)(?=(\d{3})+(?!\d))/', '.', $cuerpo) . '-' . $dv;
            },
        );
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function proyectos(): HasMany
    {
        return $this->hasMany(Proyecto::class);
    }

    public function emergencias(): HasMany
    {
        return $this->hasMany(Emergencia::class);
    }

    public function voluntarios(): HasMany
    {
        return $this->hasMany(Voluntario::class);
    }

    public function inventarioItems(): HasMany
    {
        return $this->hasMany(InventarioItem::class);
    }

    public function reuniones(): HasMany
    {
        return $this->hasMany(Reunion::class);
    }
}
