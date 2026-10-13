<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\RestablecerContrasenaMail;
use App\Support\Avatar;
use Database\Factories\UserFactory;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatarUrl();
    }

    public function avatarUrl(): string
    {
        if ($this->avatar_path && Storage::disk('uploads')->exists($this->avatar_path)) {
            return Storage::disk('uploads')->url($this->avatar_path);
        }

        return Avatar::url($this->name);
    }

    public const ROLE_ADMIN_CENTRAL = 'admin_central';

    public const ROLES_DIRECTIVA = ['presidente', 'secretario', 'tesorero', 'director', self::ROLE_ADMIN_CENTRAL];

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
        'avatar_path',
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

    public function sendPasswordResetNotification($token): void
    {
        $url = Filament::getResetPasswordUrl($token, $this);

        Mail::to($this->email)
            ->send(new RestablecerContrasenaMail($this, $url));
    }

    public function vecino()
    {
        return $this->hasOne(Vecino::class);
    }

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
