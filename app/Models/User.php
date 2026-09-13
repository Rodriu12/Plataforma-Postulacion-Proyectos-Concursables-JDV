<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rut',
        'phone',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function vecino()
    {
        return $this->hasOne(Vecino::class);
    }

    protected static function booted()
    {
        static::created(function ($user) {
            // Crea automáticamente un registro base en vecinos cuando nace un nuevo usuario
            \App\Models\Vecino::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nombre' => $user->name,
                    'rut' => 'Por definir',
                    'direccion' => 'Por definir',
                    'sector' => 'Cerro Parra',
                    'estado' => 'pendiente',
                ]
            );
        });
    }
}
