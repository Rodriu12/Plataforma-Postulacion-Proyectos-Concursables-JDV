<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nombre completo'),
                TextEntry::make('email')
                    ->label('Correo electrónico'),
                TextEntry::make('rut')
                    ->label('RUT')
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->label('Teléfono')
                    ->placeholder('-'),
                TextEntry::make('role')
                    ->label('Rol')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin_central' => 'Administrador/a Central',
                        'presidente' => 'Presidente/a',
                        'secretario' => 'Secretario/a',
                        'tesorero' => 'Tesorero/a',
                        'director' => 'Director/a',
                        'vecino' => 'Vecino/a',
                        'voluntario' => 'Voluntario/a',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'admin_central' => 'primary',
                        'presidente' => 'danger',
                        'secretario' => 'warning',
                        'tesorero' => 'success',
                        'director' => 'info',
                        'voluntario' => 'primary',
                        default => 'gray',
                    }),
                IconEntry::make('is_active')
                    ->label('Cuenta activa')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                TextEntry::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
            ]);
    }
}
