<?php

namespace App\Filament\Resources\Reuniones;

use App\Filament\Resources\Reuniones\Pages\CreateReunion;
use App\Filament\Resources\Reuniones\Pages\EditReunion;
use App\Filament\Resources\Reuniones\Pages\ListReuniones;
use App\Filament\Resources\Reuniones\Schemas\ReunionForm;
use App\Filament\Resources\Reuniones\Tables\ReunionesTable;
use App\Models\Reunion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReunionResource extends Resource
{
    protected static ?string $model = Reunion::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Reuniones';

    protected static ?string $modelLabel = 'reunión';

    protected static ?string $pluralModelLabel = 'reuniones';

    protected static ?string $slug = 'reuniones';

    /**
     * Todos ven las reuniones de su propia organización (son presenciales,
     * conviene que todos se enteren); el admin_central ve todas.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $usuario = auth()->user();

        if ($usuario && ! $usuario->esAdminCentral()) {
            $query->where('organizacion_id', $usuario->organizacion_id);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return ReunionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReunionesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReuniones::route('/'),
            'create' => CreateReunion::route('/create'),
            'edit' => EditReunion::route('/{record}/edit'),
        ];
    }
}
