<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\Organizacion;
use App\Models\User;
use App\Support\Tarjetas;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid(['default' => 1, 'lg' => 2, '2xl' => 3])
            ->columns([
                Stack::make([
                    Split::make([
                        ImageColumn::make('avatar')
                            ->label('')
                            ->circular()
                            ->size(48)
                            ->grow(false)
                            ->getStateUsing(fn ($record) => $record->avatarUrl()),

                        Stack::make([
                            TextColumn::make('name')
                                ->label('Nombre')
                                ->weight('bold')
                                ->size('lg')
                                ->wrap()
                                ->searchable()
                                ->extraAttributes(Tarjetas::TEXTO_QUE_ENVUELVE),

                            TextColumn::make('email')
                                ->label('Email')
                                ->color('gray')
                                ->wrap()
                                ->searchable()
                                ->extraAttributes(Tarjetas::TEXTO_QUE_ENVUELVE),
                        ])->space(1),
                    ])->extraAttributes(['style' => 'align-items:flex-start;']),

                    Split::make([
                        TextColumn::make('role')
                            ->label('Rol')
                            ->badge()
                            ->grow(false)
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'admin_central' => 'Admin. Central',
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

                        TextColumn::make('is_active')
                            ->label('Cuenta')
                            ->badge()
                            ->grow(false)
                            ->formatStateUsing(fn ($state) => $state ? 'Activo' : 'Inactivo')
                            ->color(fn ($state) => $state ? 'success' : 'gray'),
                    ])->extraAttributes(['style' => 'flex-wrap:wrap;']),

                    Stack::make([
                        Tarjetas::linea('rut', 'RUT'),
                        Tarjetas::linea('phone', 'Teléfono'),
                        TextColumn::make('organizacion.nombre')
                            ->label('Organización')
                            ->formatStateUsing(fn ($state, User $record) => 'Organización: ' . (filled($record->organizacion_id) ? ($record->organizacion?->nombre ?? '—') : '⚠️ Sin junta asignada (Pendiente)'))
                            ->color(fn (User $record) => empty($record->organizacion_id) ? 'danger' : 'gray')
                            ->wrap()
                            ->extraAttributes(Tarjetas::TEXTO_QUE_ENVUELVE),
                    ])->space(1),
                ])->space(3),
            ])
            ->filters([
                SelectFilter::make('organizacion_id')
                    ->label('Organización')
                    ->relationship('organizacion', 'nombre')
                    ->visible(fn () => auth()->user()?->esAdminCentral()),

                SelectFilter::make('estado_organizacion')
                    ->label('Estado de asignación')
                    ->options([
                        'con_organizacion' => 'Con junta/organización',
                        'sin_organizacion' => 'Sin junta (Pendientes)',
                    ])
                    ->query(function ($query, array $data) {
                        if (($data['value'] ?? null) === 'con_organizacion') {
                            return $query->whereNotNull('organizacion_id');
                        }
                        if (($data['value'] ?? null) === 'sin_organizacion') {
                            return $query->whereNull('organizacion_id');
                        }
                        return $query;
                    })
                    ->visible(fn () => auth()->user()?->esAdminCentral()),
            ])
            ->recordActions([
                Action::make('asignarOrganizacion')
                    ->label('Asignar Junta')
                    ->icon('heroicon-m-building-office-2')
                    ->color('warning')
                    ->visible(fn (User $record) => auth()->user()?->esAdminCentral() && empty($record->organizacion_id))
                    ->modalHeading(fn (User $record) => "Asignar Organización a {$record->name}")
                    ->modalDescription('Al asignar la organización, la cuenta se activará y se enviará automáticamente el correo para que la persona pueda establecer su contraseña e interactuar en la plataforma.')
                    ->modalSubmitActionLabel('Asignar y Enviar Correo')
                    ->form([
                        Select::make('organizacion_id')
                            ->label('Organización / Junta de Vecinos')
                            ->options(Organizacion::pluck('nombre', 'id'))
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update([
                            'organizacion_id' => $data['organizacion_id'],
                            'is_active' => true,
                        ]);

                        Notification::make()
                            ->title('Organización asignada')
                            ->body("Se asignó la organización a {$record->name} y se envió el correo para definir contraseña.")
                            ->success()
                            ->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
