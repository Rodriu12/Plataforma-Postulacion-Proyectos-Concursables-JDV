<?php

namespace App\Support;

use Filament\Tables\Columns\TextColumn;
class Tarjetas
{
    public const TEXTO_QUE_ENVUELVE = ['style' => 'overflow-wrap:anywhere;word-break:break-word;'];

    public static function linea(string $campo, string $etiqueta): TextColumn
    {
        return TextColumn::make($campo)
            ->formatStateUsing(fn ($state) => $etiqueta . ': ' . (filled($state) ? $state : '—'))
            ->color('gray')
            ->wrap()
            ->extraAttributes(self::TEXTO_QUE_ENVUELVE);
    }
}
