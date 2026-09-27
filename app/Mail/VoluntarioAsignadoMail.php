<?php

namespace App\Mail;

use App\Models\Emergencia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VoluntarioAsignadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public Emergencia $emergencia;

    public function __construct(Emergencia $emergencia)
    {
        $this->emergencia = $emergencia;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vecindar: Fuiste asignado a una emergencia',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.voluntario-asignado',
        );
    }
}
