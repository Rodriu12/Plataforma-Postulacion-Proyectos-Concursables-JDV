<?php

namespace App\Filament\Resources\Proyectos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProyectoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('organizacion.id')
                    ->label('Organizacion'),
                TextEntry::make('titulo'),
                TextEntry::make('descripcion')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('fuente_financiamiento')
                    ->placeholder('-'),
                TextEntry::make('monto_solicitado')
                    ->numeric(),
                TextEntry::make('monto_adjudicado')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('estado')
                    ->badge(),
                TextEntry::make('fecha_postulacion')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('fecha_adjudicacion')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
