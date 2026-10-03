<?php

namespace App\Filament\Resources\Proyectos;

use App\Filament\Resources\Proyectos\Pages\CreateProyecto;
use App\Filament\Resources\Proyectos\Pages\EditProyecto;
use App\Filament\Resources\Proyectos\Pages\ListProyectos;
use App\Filament\Resources\Proyectos\Pages\ViewProyecto;
use App\Filament\Resources\Proyectos\Schemas\ProyectoForm;
use App\Filament\Resources\Proyectos\Schemas\ProyectoInfolist;
use App\Filament\Resources\Proyectos\Tables\ProyectosTable;
use App\Models\Proyecto;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $recordTitleAttribute = 'model Proyecto';

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
        return ProyectoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProyectoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProyectosTable::configure($table);
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
            'index' => ListProyectos::route('/'),
            'create' => CreateProyecto::route('/create'),
            'view' => ViewProyecto::route('/{record}'),
            'edit' => EditProyecto::route('/{record}/edit'),
        ];
    }
}
