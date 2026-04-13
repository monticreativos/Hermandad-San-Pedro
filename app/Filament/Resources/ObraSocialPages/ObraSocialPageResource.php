<?php

namespace App\Filament\Resources\ObraSocialPages;

use App\Filament\Resources\ObraSocialPages\Pages\CreateObraSocialPage;
use App\Filament\Resources\ObraSocialPages\Pages\EditObraSocialPage;
use App\Filament\Resources\ObraSocialPages\Pages\ListObraSocialPages;
use App\Filament\Resources\ObraSocialPages\Schemas\ObraSocialPageForm;
use App\Filament\Resources\ObraSocialPages\Tables\ObraSocialPagesTable;
use App\Models\ObraSocialPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ObraSocialPageResource extends Resource
{
    protected static ?string $model = ObraSocialPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static ?string $navigationLabel = 'Paginas Obra Social';

    protected static ?string $recordTitleAttribute = 'key';

    protected static ?int $navigationSort = 28;

    public static function form(Schema $schema): Schema
    {
        return ObraSocialPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ObraSocialPagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListObraSocialPages::route('/'),
            'create' => CreateObraSocialPage::route('/create'),
            'edit' => EditObraSocialPage::route('/{record}/edit'),
        ];
    }
}
