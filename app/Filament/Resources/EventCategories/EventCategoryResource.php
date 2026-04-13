<?php

namespace App\Filament\Resources\EventCategories;

use App\Filament\Resources\EventCategories\Pages\CreateEventCategory;
use App\Filament\Resources\EventCategories\Pages\EditEventCategory;
use App\Filament\Resources\EventCategories\Pages\ListEventCategories;
use App\Filament\Resources\EventCategories\Schemas\EventCategoryForm;
use App\Filament\Resources\EventCategories\Tables\EventCategoriesTable;
use App\Models\EventCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class EventCategoryResource extends Resource
{
    protected static ?string $model = EventCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static ?string $navigationLabel = 'Tipos de acto';

    protected static ?string $modelLabel = 'Tipo de acto';

    protected static ?string $pluralModelLabel = 'Tipos de acto';

    protected static string|UnitEnum|null $navigationGroup = 'Agenda';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'slug';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('events');
    }

    public static function form(Schema $schema): Schema
    {
        return EventCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEventCategories::route('/'),
            'create' => CreateEventCategory::route('/create'),
            'edit' => EditEventCategory::route('/{record}/edit'),
        ];
    }
}
