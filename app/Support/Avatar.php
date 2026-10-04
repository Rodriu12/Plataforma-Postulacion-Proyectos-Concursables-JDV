<?php

namespace App\Support;

class Avatar
{
    /**
     * URL de un avatar circular con las iniciales del nombre, usando la
     * paleta azul de Vecindar (#1d4ed8) por defecto. Se usa en las tablas
     * tipo tarjeta porque ningún modelo tiene foto de perfil real.
     */
    public static function url(?string $nombre, string $colorFondo = '1d4ed8', string $colorTexto = 'ffffff'): string
    {
        $nombre = trim((string) $nombre) ?: '?';

        return 'https://ui-avatars.com/api/?' . http_build_query([
            'name' => $nombre,
            'background' => $colorFondo,
            'color' => $colorTexto,
            'bold' => 'true',
            'size' => 128,
        ]);
    }
}
