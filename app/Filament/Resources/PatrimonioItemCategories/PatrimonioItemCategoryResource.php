<?php

namespace App\Filament\Resources\PatrimonioItemCategories;

use App\Filament\Resources\PatrimonioItemCategories\Pages\CreatePatrimonioItemCategory;
use App\Filament\Resources\PatrimonioItemCategories\Pages\EditPatrimonioItemCategory;
use App\Filament\Resources\PatrimonioItemCategories\Pages\ListPatrimonioItemCategories;
use App\Filament\Resources\PatrimonioItemCategories\Schemas\PatrimonioItemCategoryForm;
use App\Filament\Resources\PatrimonioItemCategories\Tables\PatrimonioItemCategoriesTable;
use App\Models\PatrimonioItemCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PatrimonioItemCategoryResource extends Resource
{
    protected static ?string $model = PatrimonioItemCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Categorías de enseres';

    protected static ?string $modelLabel = 'Categoría de enseres';

    protected static ?string $pluralModelLabel = 'Categorías de enseres';

    protected static string|UnitEnum|null $navigationGroup = 'Patrimonio';

    protected static ?int $navigationSort = 28;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('section_key', 'enseres')
            ->withCount([
                'items' => fn ($query) => $query->where('section_key', 'enseres'),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return PatrimonioItemCategoryForm::configure($schema, 'enseres');
    }

    public static function table(Table $table): Table
    {
        return PatrimonioItemCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatrimonioItemCategories::route('/'),
            'create' => CreatePatrimonioItemCategory::route('/create'),
            'edit' => EditPatrimonioItemCategory::route('/{record}/edit'),
        ];
    }
}
