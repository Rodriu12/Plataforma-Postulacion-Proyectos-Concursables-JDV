<?php

namespace App\Console\Commands;

use App\Mail\ReunionRecordatorioMail;
use App\Models\Reunion;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnviarRecordatoriosReuniones extends Command
{
    protected $signature = 'app:enviar-recordatorios-reuniones';

    protected $description = 'Envía recordatorios de reuniones un día y una hora antes';

    public function handle(): int
    {
        $ahora = now();

        $reuniones = Reunion::query()
            ->where('estado', 'programada')
            ->whereNotNull('fecha_hora')
            ->get();

        foreach ($reuniones as $reunion) {
            if ($reunion->fecha_hora->isPast()) {
                continue;
            }

            $diaAntes = $reunion->fecha_hora->copy()->subDay();
            $horaAntes = $reunion->fecha_hora->copy()->subHour();

            if (is_null($reunion->recordatorio_dia_enviado_at) && $ahora->between($diaAntes->copy()->subMinutes(5), $diaAntes->copy()->addMinutes(5))) {
                $this->enviarRecordatorio($reunion, 'dia');
            }

            if (is_null($reunion->recordatorio_hora_enviado_at) && $ahora->between($horaAntes->copy()->subMinutes(5), $horaAntes->copy()->addMinutes(5))) {
                $this->enviarRecordatorio($reunion, 'hora');
            }
        }

        return self::SUCCESS;
    }

    protected function enviarRecordatorio(Reunion $reunion, string $tipo): void
    {
        $miembros = User::where('organizacion_id', $reunion->organizacion_id)->get();

        foreach ($miembros as $usuario) {
            $mensaje = $tipo === 'dia'
                ? 'Mañana te esperamos en la reunión: ' . $reunion->titulo
                : 'La reunión empieza en una hora: ' . $reunion->titulo;

            FilamentNotification::make()
                ->title($tipo === 'dia' ? 'Recordatorio de reunión' : 'Queda 1 hora para la reunión')
                ->body($mensaje . ' — ' . $reunion->fecha_hora->translatedFormat('d \d\e F \a \l\a\s H:i') . ' — ' . $reunion->lugar)
                ->icon('heroicon-o-bell-alert')
                ->color('warning')
                ->sendToDatabase($usuario);

            if (filled($usuario->email)) {
                Mail::to($usuario->email)->send(new ReunionRecordatorioMail($reunion, $tipo));
            }
        }

        if ($tipo === 'dia') {
            $reunion->recordatorio_dia_enviado_at = now();
        } else {
            $reunion->recordatorio_hora_enviado_at = now();
        }

        $reunion->saveQuietly();
    }
}
