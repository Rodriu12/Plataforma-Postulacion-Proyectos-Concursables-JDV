<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Rol del administrador central de la plataforma (ve y gestiona TODAS
     * las organizaciones, sin quedar acotado a una sola). Es distinto de
     * 'presidente' precisamente para poder diferenciarlo en el código.
     */
    public const ROLE_ADMIN_CENTRAL = 'admin_central';

    /**
     * Roles que forman parte de la directiva de UNA organización.
     * Tienen acceso de gestión a Organizaciones, Proyectos, Vecinos,
     * Voluntarios y Emergencias — pero acotado a su propia organización
     * (ver getEloquentQuery() de cada Resource). admin_central se incluye
     * aquí para heredar automáticamente estos mismos permisos base; el
     * acotamiento por organización se omite para él en cada Resource.
     */
    public const ROLES_DIRECTIVA = ['presidente', 'secretario', 'tesorero', 'director', self::ROLE_ADMIN_CENTRAL];

    /**
     * Roles con permiso para gestionar cuentas de usuario (el módulo más sensible).
     * Subconjunto de la directiva: presidente, secretario y admin_central.
     */
    public const ROLES_GESTION_USUARIOS = ['presidente', 'secretario', self::ROLE_ADMIN_CENTRAL];

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

    /**
     * Reemplaza el correo de restablecimiento de contraseña por defecto de
     * Laravel (en inglés, sin marca) por el de Vecindar en español. Se
     * dispara tanto por "olvidé mi contraseña" en el login como por
     * Password::sendResetLink() desde el importador de Excel.
     */
    public function sendPasswordResetNotification($token): void
    {
        $url = \Filament\Facades\Filament::getResetPasswordUrl($token, $this);

        \Illuminate\Support\Facades\Mail::to($this->email)
            ->send(new \App\Mail\RestablecerContrasenaMail($this, $url));
    }

    public function vecino()
    {
        return $this->hasOne(Vecino::class);
    }

    /**
     * Normaliza el rol a minúsculas y sin espacios al guardarlo, sin importar
     * cómo se haya escrito (formulario, seeder, import manual, etc.), para
     * que siempre coincida con los valores usados en ROLES_DIRECTIVA y en
     * las Policies.
     */
    protected function role(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value !== null ? strtolower(trim($value)) : $value,
        );
    }

    public function voluntario()
    {
        return $this->hasOne(Voluntario::class);
    }

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function emergencias()
    {
        return $this->hasManyThrough(Emergencia::class, Vecino::class);
    }

    /**
     * El admin central ve y gestiona TODAS las organizaciones; todo lo
     * demás (directiva normal, vecino, voluntario) queda acotado a la suya.
     */
    public function esAdminCentral(): bool
    {
        return $this->role === self::ROLE_ADMIN_CENTRAL;
    }

    protected static function booted()
    {
        static::created(function ($user) {
            Vecino::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nombre' => $user->name,
                    'rut' => $user->rut ?: null,
                    'direccion' => 'Por definir',
                    'sector' => 'Cerro Parra',
                    'estado' => 'pendiente',
                ]
            );

            if ($user->role === 'voluntario') {
                Voluntario::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'organizacion_id' => $user->organizacion_id,
                        'nombre' => $user->name,
                        'telefono' => $user->phone,
                        'sector' => 'Por definir',
                        'area_apoyo' => 'otro',
                        'disponibilidad' => 'Por definir',
                        'estado' => 'pendiente',
                    ]
                );
            }
        });
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
