<?php

namespace App\Filament\Resources\BrotherhoodPages;

use App\Filament\Resources\BrotherhoodPages\Pages\CreateBrotherhoodPage;
use App\Filament\Resources\BrotherhoodPages\Pages\EditBrotherhoodPage;
use App\Filament\Resources\BrotherhoodPages\Pages\ListBrotherhoodPages;
use App\Filament\Resources\BrotherhoodPages\Schemas\BrotherhoodPageForm;
use App\Filament\Resources\BrotherhoodPages\Tables\BrotherhoodPagesTable;
use App\Models\BrotherhoodPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BrotherhoodPageResource extends Resource
{
    protected static ?string $model = BrotherhoodPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static ?string $navigationLabel = 'Paginas Hermandad';

    protected static ?string $recordTitleAttribute = 'key';

    public static function form(Schema $schema): Schema
    {
        return BrotherhoodPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrotherhoodPagesTable::configure($table);
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
            'index' => ListBrotherhoodPages::route('/'),
            'create' => CreateBrotherhoodPage::route('/create'),
            'edit' => EditBrotherhoodPage::route('/{record}/edit'),
        ];
    }
}
