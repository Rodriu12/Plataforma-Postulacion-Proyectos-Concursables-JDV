<?php

namespace App\Filament\Resources\Voluntarios;

use App\Filament\Resources\Voluntarios\Pages\CreateVoluntario;
use App\Filament\Resources\Voluntarios\Pages\EditVoluntario;
use App\Filament\Resources\Voluntarios\Pages\ListVoluntarios;
use App\Filament\Resources\Voluntarios\Schemas\VoluntarioForm;
use App\Filament\Resources\Voluntarios\Tables\VoluntariosTable;
use App\Models\User;
use App\Models\Voluntario;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VoluntarioResource extends Resource
{
    protected static ?string $model = Voluntario::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-hand-raised';

    protected static ?string $navigationLabel = 'Voluntarios';

    protected static ?string $modelLabel = 'voluntario';

    protected static ?string $pluralModelLabel = 'voluntarios';

    /**
     * Un voluntario solo ve y gestiona su propio registro.
     * La directiva ve y aprueba todos los registros.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $usuario = auth()->user();

        if ($usuario && ! in_array($usuario->role, User::ROLES_DIRECTIVA)) {
            $query->where('user_id', $usuario->id);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return VoluntarioForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VoluntariosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVoluntarios::route('/'),
            'create' => CreateVoluntario::route('/create'),
            'edit' => EditVoluntario::route('/{record}/edit'),
        ];
    }
}
