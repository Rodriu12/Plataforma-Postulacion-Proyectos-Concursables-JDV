<?php

namespace App\Notifications;

use App\Models\Vecino;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\Notification;

class VecinoEstadoNotification extends Notification
{
    public function __construct(public Vecino $vecino)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $aprobado = $this->vecino->estado === 'aprobado';

        $filamentNotification = FilamentNotification::make()
            ->title($aprobado ? 'Tu registro como vecino fue aprobado' : 'Tu registro como vecino fue rechazado')
            ->body($aprobado
                ? 'Tu registro como vecino fue aprobado correctamente.'
                : 'Tu registro como vecino fue rechazado. Revisa la información asociada.')
            ->icon('heroicon-o-identification')
            ->status($aprobado ? 'success' : 'danger')
            ->color($aprobado ? 'success' : 'danger');

        $data = $filamentNotification->getDatabaseMessage();
        $data['vecino_id'] = $this->vecino->id;

        return $data;
    }
}
