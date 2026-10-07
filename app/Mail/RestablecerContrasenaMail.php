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

    /**
     * Se usa tanto para "olvidé mi contraseña" como para el primer ingreso
     * de alguien creado vía la importación de Excel (en ambos casos Laravel
     * llama a User::sendPasswordResetNotification(), que redirige aquí).
     */
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
