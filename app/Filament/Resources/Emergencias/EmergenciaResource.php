<?php

namespace App\Filament\Resources\Emergencias;

use App\Filament\Resources\Emergencias\Pages\CreateEmergencia;
use App\Filament\Resources\Emergencias\Pages\EditEmergencia;
use App\Filament\Resources\Emergencias\Pages\ListEmergencias;
use App\Filament\Resources\Emergencias\Schemas\EmergenciaForm;
use App\Filament\Resources\Emergencias\Tables\EmergenciasTable;
use App\Models\Emergencia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmergenciaResource extends Resource
{
    protected static ?string $model = Emergencia::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Emergencias';

    protected static ?string $modelLabel = 'emergencia';

    protected static ?string $pluralModelLabel = 'emergencias';

    /**
     * Un vecino solo ve y gestiona sus propios reportes.
     * La directiva (presidente, secretario, tesorero, director) ve todo.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $usuario = auth()->user();

        if ($usuario && ! in_array($usuario->role, Emergencia::ROLES_DIRECTIVA)) {
            $query->where(function (Builder $q) use ($usuario) {
                $q->whereHas('vecino', fn (Builder $v) => $v->where('user_id', $usuario->id))
                    ->orWhereHas('voluntario', fn (Builder $v) => $v->where('user_id', $usuario->id));
            });
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return EmergenciaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmergenciasTable::configure($table);
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
            'index' => ListEmergencias::route('/'),
            'create' => CreateEmergencia::route('/create'),
            'edit' => EditEmergencia::route('/{record}/edit'),
        ];
    }
}
