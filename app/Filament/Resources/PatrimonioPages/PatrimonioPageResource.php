<?php

namespace App\Filament\Resources\PatrimonioPages;

use App\Filament\Resources\PatrimonioPages\Pages\CreatePatrimonioPage;
use App\Filament\Resources\PatrimonioPages\Pages\EditPatrimonioPage;
use App\Filament\Resources\PatrimonioPages\Pages\ListPatrimonioPages;
use App\Filament\Resources\PatrimonioPages\Schemas\PatrimonioPageForm;
use App\Filament\Resources\PatrimonioPages\Tables\PatrimonioPagesTable;
use App\Models\PatrimonioPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatrimonioPageResource extends Resource
{
    protected static ?string $model = PatrimonioPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static ?string $navigationLabel = 'Paginas Patrimonio';

    protected static ?string $recordTitleAttribute = 'key';

    protected static ?int $navigationSort = 27;

    public static function form(Schema $schema): Schema
    {
        return PatrimonioPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatrimonioPagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatrimonioPages::route('/'),
            'create' => CreatePatrimonioPage::route('/create'),
            'edit' => EditPatrimonioPage::route('/{record}/edit'),
        ];
    }
}
