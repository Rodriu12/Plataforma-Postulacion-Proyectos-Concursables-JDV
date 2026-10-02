<?php

namespace App\Filament\Resources\Vecinos;

use App\Filament\Resources\Vecinos\Pages\CreateVecino;
use App\Filament\Resources\Vecinos\Pages\EditVecino;
use App\Filament\Resources\Vecinos\Pages\ListVecinos;
use App\Filament\Resources\Vecinos\Schemas\VecinoForm;
use App\Filament\Resources\Vecinos\Tables\VecinosTable;
use App\Models\User;
use App\Models\Vecino;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VecinoResource extends Resource
{
    protected static ?string $model = Vecino::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static ?string $recordTitleAttribute = 'model-vecino';

    
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $usuario = auth()->user();

        if (! $usuario) {
            return $query;
        }

        if ($usuario->esAdminCentral()) {
            return $query;
        }

        if (in_array($usuario->role, User::ROLES_DIRECTIVA)) {
            return $query->whereHas('user', fn (Builder $q) => $q->where('organizacion_id', $usuario->organizacion_id));
        }

        return $query->where('user_id', $usuario->id);
    }

    public static function form(Schema $schema): Schema
    {
        return VecinoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VecinosTable::configure($table);
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
            'index' => ListVecinos::route('/'),
            'create' => CreateVecino::route('/create'),
            'edit' => EditVecino::route('/{record}/edit'),
        ];
    }
}
