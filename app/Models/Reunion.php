<?php

namespace App\Models;

use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reunion extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizacion_id',
        'user_id',
        'titulo',
        'descripcion',
        'fecha_hora',
        'lugar',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_hora' => 'datetime',
        ];
    }

    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function convocadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted(): void
    {
        static::created(function (Reunion $reunion) {
            $reunion->notificarAOrganizacion();
        });
    }

    /**
     * Avisa por la campanita (notificación de base de datos de Filament) a
     * todos los miembros de la organización: directiva, vecinos y
     * voluntarios. Las reuniones son presenciales, así que no se manda
     * correo, solo el aviso dentro de la plataforma.
     */
    public function notificarAOrganizacion(): void
    {
        $miembros = User::where('organizacion_id', $this->organizacion_id)->get();

        foreach ($miembros as $usuario) {
            FilamentNotification::make()
                ->title('Nueva reunión: ' . $this->titulo)
                ->body($this->fecha_hora->translatedFormat('d \d\e F \a \l\a\s H:i') . ' — ' . $this->lugar)
                ->icon('heroicon-o-calendar-days')
                ->color('info')
                ->sendToDatabase($usuario);
        }
    }
}
