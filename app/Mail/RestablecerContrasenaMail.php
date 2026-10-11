<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RestablecerContrasenaMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $usuario;

    public string $url;
    public function __construct(User $usuario, string $url)
    {
        $this->usuario = $usuario;
        $this->url = $url;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vecindar: Define tu contraseña',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.restablecer-contrasena',
        );
    }
}
