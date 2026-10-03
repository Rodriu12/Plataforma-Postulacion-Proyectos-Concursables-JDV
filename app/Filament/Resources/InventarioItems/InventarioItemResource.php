<?php

namespace App\Filament\Resources\InventarioItems;

use App\Filament\Resources\InventarioItems\Pages\CreateInventarioItem;
use App\Filament\Resources\InventarioItems\Pages\EditInventarioItem;
use App\Filament\Resources\InventarioItems\Pages\ListInventarioItems;
use App\Filament\Resources\InventarioItems\RelationManagers\MovimientosRelationManager;
use App\Filament\Resources\InventarioItems\RelationManagers\PrestamosRelationManager;
use App\Filament\Resources\InventarioItems\Schemas\InventarioItemForm;
use App\Filament\Resources\InventarioItems\Tables\InventarioItemsTable;
use App\Models\InventarioItem;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InventarioItemResource extends Resource
{
    protected static ?string $model = InventarioItem::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Inventario';

    protected static ?string $modelLabel = 'ítem de inventario';

    protected static ?string $pluralModelLabel = 'Inventario';

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
        return InventarioItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventarioItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            MovimientosRelationManager::class,
            PrestamosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventarioItems::route('/'),
            'create' => CreateInventarioItem::route('/create'),
            'edit' => EditInventarioItem::route('/{record}/edit'),
        ];
    }
}
