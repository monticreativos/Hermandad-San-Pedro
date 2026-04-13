<?php

namespace App\Filament\Resources\CultosPages;

use App\Filament\Resources\CultosPages\Pages\CreateCultosPage;
use App\Filament\Resources\CultosPages\Pages\EditCultosPage;
use App\Filament\Resources\CultosPages\Pages\ListCultosPages;
use App\Filament\Resources\CultosPages\Schemas\CultosPageForm;
use App\Filament\Resources\CultosPages\Tables\CultosPagesTable;
use App\Models\CultosPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CultosPageResource extends Resource
{
    protected static ?string $model = CultosPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Paginas Cultos';

    protected static ?string $recordTitleAttribute = 'key';

    protected static string|UnitEnum|null $navigationGroup = 'Cultos';

    protected static ?int $navigationSort = 26;

    public static function form(Schema $schema): Schema
    {
        return CultosPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CultosPagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCultosPages::route('/'),
            'create' => CreateCultosPage::route('/create'),
            'edit' => EditCultosPage::route('/{record}/edit'),
        ];
    }
}
