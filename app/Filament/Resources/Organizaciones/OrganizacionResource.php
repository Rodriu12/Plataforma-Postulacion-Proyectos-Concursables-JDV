<?php

namespace App\Filament\Resources\Organizaciones;

use App\Filament\Resources\Organizaciones\Pages\CreateOrganizacion;
use App\Filament\Resources\Organizaciones\Pages\EditOrganizacion;
use App\Filament\Resources\Organizaciones\Pages\ListOrganizacion;
use App\Filament\Resources\Organizaciones\Pages\ViewOrganizacion;
use App\Filament\Resources\Organizaciones\Schemas\OrganizacionForm;
use App\Filament\Resources\Organizaciones\Schemas\OrganizacionInfolist;
use App\Filament\Resources\Organizaciones\Tables\OrganizacionTable;
use App\Models\Organizacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrganizacionResource extends Resource
{
    protected static ?string $model = Organizacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Organizaciones';

    protected static ?string $pluralModelLabel = 'Organizaciones';

    protected static ?string $modelLabel = 'Organización';

    protected static ?string $slug = 'organizaciones';
    protected static ?string $recordTitleAttribute = 'model organizacion';

    public static function form(Schema $schema): Schema
    {
        return OrganizacionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrganizacionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrganizacionTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProyectosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrganizacion::route('/'),
            'create' => CreateOrganizacion::route('/create'),
            'view' => ViewOrganizacion::route('/{record}'),
            'edit' => EditOrganizacion::route('/{record}/edit'),
        ];
    }
}
