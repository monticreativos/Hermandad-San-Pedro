<?php

namespace App\Filament\Resources\PatrimonioItems;

use App\Filament\Resources\PatrimonioItems\Pages\CreatePatrimonioItem;
use App\Filament\Resources\PatrimonioItems\Pages\EditPatrimonioItem;
use App\Filament\Resources\PatrimonioItems\Pages\ListPatrimonioItems;
use App\Filament\Resources\PatrimonioItems\Schemas\PatrimonioItemForm;
use App\Filament\Resources\PatrimonioItems\Tables\PatrimonioItemsTable;
use App\Models\PatrimonioItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PatrimonioItemResource extends Resource
{
    protected static ?string $model = PatrimonioItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $navigationLabel = 'Enseres (patrimonio)';

    protected static ?string $modelLabel = 'Enser';

    protected static ?string $pluralModelLabel = 'Enseres';

    protected static string|UnitEnum|null $navigationGroup = 'Patrimonio';

    protected static ?int $navigationSort = 29;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('section_key', 'enseres');
    }

    public static function form(Schema $schema): Schema
    {
        return PatrimonioItemForm::configure($schema, 'enseres');
    }

    public static function table(Table $table): Table
    {
        return PatrimonioItemsTable::configure($table, 'enseres');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatrimonioItems::route('/'),
            'create' => CreatePatrimonioItem::route('/create'),
            'edit' => EditPatrimonioItem::route('/{record}/edit'),
        ];
    }
}
