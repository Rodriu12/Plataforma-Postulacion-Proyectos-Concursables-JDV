<?php

namespace App\Notifications;

use App\Filament\Resources\Vecinos\VecinoResource;
use App\Models\Vecino;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\Notification;

class VecinoComprobanteRevisionNotification extends Notification
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
        $url = null;
        try {
            if (class_exists(VecinoResource::class) && filament()->isCurrentPanel()) {
                $url = VecinoResource::getUrl('edit', ['record' => $this->vecino]);
            } else {
                $url = '/admin/vecinos/' . $this->vecino->id . '/edit';
            }
        } catch (\Throwable) {
            $url = '/admin/vecinos/' . $this->vecino->id . '/edit';
        }

        $filamentNotification = FilamentNotification::make()
            ->title('Revisión de comprobante requerida')
            ->body('El vecino ' . $this->vecino->nombre . ' subió un comprobante que no coincide con sus datos (' . ($this->vecino->comprobante_motivo ?: 'dirección no coincide') . '). Revisa el documento por segunda vez antes de aprobarlo.')
            ->warning()
            ->icon('heroicon-o-exclamation-triangle')
            ->iconColor('warning');

        if ($url) {
            $filamentNotification->actions([
                Action::make('revisar')
                    ->label('Revisar Vecino')
                    ->button()
                    ->url($url),
            ]);
        }

        $data = $filamentNotification->getDatabaseMessage();
        $data['vecino_id'] = $this->vecino->id;

        return $data;
    }
}
