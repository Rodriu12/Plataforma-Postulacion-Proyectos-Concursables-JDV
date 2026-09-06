<?php

namespace App\Filament\Resources\ProyectoExternos;

use App\Filament\Resources\ProyectoExternos\Pages\CreateProyectoExterno;
use App\Filament\Resources\ProyectoExternos\Pages\EditProyectoExterno;
use App\Filament\Resources\ProyectoExternos\Pages\ListProyectoExternos;
use App\Filament\Resources\ProyectoExternos\Pages\ViewProyectoExterno;
use App\Filament\Resources\ProyectoExternos\Schemas\ProyectoExternoForm;
use App\Filament\Resources\ProyectoExternos\Schemas\ProyectoExternoInfolist;
use App\Filament\Resources\ProyectoExternos\Tables\ProyectoExternosTable;
use App\Models\ProyectoExterno;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProyectoExternoResource extends Resource
{
    protected static ?string $model = ProyectoExterno::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'model proyectoexterno';

    public static function form(Schema $schema): Schema
    {
        return ProyectoExternoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProyectoExternoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProyectoExternosTable::configure($table);
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
            'index' => ListProyectoExternos::route('/'),
            'create' => CreateProyectoExterno::route('/create'),
            'view' => ViewProyectoExterno::route('/{record}'),
            'edit' => EditProyectoExterno::route('/{record}/edit'),
        ];
    }
}
