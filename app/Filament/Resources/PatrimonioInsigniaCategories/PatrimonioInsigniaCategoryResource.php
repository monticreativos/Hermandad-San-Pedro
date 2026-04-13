<?php

namespace App\Filament\Resources\PatrimonioInsigniaCategories;

use App\Filament\Resources\PatrimonioInsigniaCategories\Pages\CreatePatrimonioInsigniaCategory;
use App\Filament\Resources\PatrimonioInsigniaCategories\Pages\EditPatrimonioInsigniaCategory;
use App\Filament\Resources\PatrimonioInsigniaCategories\Pages\ListPatrimonioInsigniaCategories;
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

class PatrimonioInsigniaCategoryResource extends Resource
{
    protected static ?string $model = PatrimonioItemCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Categorías (insignia)';

    protected static ?string $modelLabel = 'Categoría de insignia';

    protected static ?string $pluralModelLabel = 'Categorías de insignia';

    protected static string|UnitEnum|null $navigationGroup = 'Patrimonio';

    protected static ?int $navigationSort = 30;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('section_key', 'insignia-cofradia')
            ->withCount([
                'items' => fn ($query) => $query->where('section_key', 'insignia-cofradia'),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return PatrimonioItemCategoryForm::configure($schema, 'insignia-cofradia');
    }

    public static function table(Table $table): Table
    {
        return PatrimonioItemCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatrimonioInsigniaCategories::route('/'),
            'create' => CreatePatrimonioInsigniaCategory::route('/create'),
            'edit' => EditPatrimonioInsigniaCategory::route('/{record}/edit'),
        ];
    }
}
