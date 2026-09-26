<?php

namespace App\Mail;

use App\Models\Emergencia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevaEmergenciaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Emergencia $emergencia;

    /**
     * Create a new message instance.
     */
    public function __construct(Emergencia $emergencia)
    {
        $this->emergencia = $emergencia;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vecindar: Nueva emergencia reportada - ' . ucfirst($this->emergencia->tipo),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.nueva-emergencia',
        );
    }
}
