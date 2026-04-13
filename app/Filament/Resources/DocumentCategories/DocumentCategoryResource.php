<?php

namespace App\Filament\Resources\DocumentCategories;

use App\Filament\Resources\DocumentCategories\Pages\CreateDocumentCategory;
use App\Filament\Resources\DocumentCategories\Pages\EditDocumentCategory;
use App\Filament\Resources\DocumentCategories\Pages\ListDocumentCategories;
use App\Filament\Resources\DocumentCategories\Schemas\DocumentCategoryForm;
use App\Filament\Resources\DocumentCategories\Tables\DocumentCategoriesTable;
use App\Models\DocumentCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DocumentCategoryResource extends Resource
{
    protected static ?string $model = DocumentCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $navigationLabel = 'Categorias de documentacion';

    protected static ?string $modelLabel = 'Categoria';

    protected static ?string $pluralModelLabel = 'Categorias';

    protected static string|UnitEnum|null $navigationGroup = 'Documentacion';

    public static function form(Schema $schema): Schema
    {
        return DocumentCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentCategories::route('/'),
            'create' => CreateDocumentCategory::route('/create'),
            'edit' => EditDocumentCategory::route('/{record}/edit'),
        ];
    }
}
