<?php

namespace App\Mail;

use App\Models\Reunion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReunionRecordatorioMail extends Mailable
{
    use Queueable, SerializesModels;

    public Reunion $reunion;

    public string $tipo;

    public function __construct(Reunion $reunion, string $tipo)
    {
        $this->reunion = $reunion;
        $this->tipo = $tipo;
    }

    public function envelope(): Envelope
    {
        $prefijo = $this->tipo === 'dia'
            ? 'Recordatorio: mañana'
            : 'Recordatorio: queda 1 hora';

        return new Envelope(
            subject: 'Vecindar: ' . $prefijo . ' - ' . $this->reunion->titulo,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recordatorio-reunion',
            with: [
                'reunion' => $this->reunion,
                'tipo' => $this->tipo,
            ],
        );
    }
}
