<?php

namespace App\Filament\Resources\PatrimonioInsigniaItems;

use App\Filament\Resources\PatrimonioInsigniaItems\Pages\CreatePatrimonioInsigniaItem;
use App\Filament\Resources\PatrimonioInsigniaItems\Pages\EditPatrimonioInsigniaItem;
use App\Filament\Resources\PatrimonioInsigniaItems\Pages\ListPatrimonioInsigniaItems;
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

class PatrimonioInsigniaItemResource extends Resource
{
    protected static ?string $model = PatrimonioItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Insignia (piezas)';

    protected static ?string $modelLabel = 'Pieza de insignia';

    protected static ?string $pluralModelLabel = 'Piezas de insignia';

    protected static string|UnitEnum|null $navigationGroup = 'Patrimonio';

    protected static ?int $navigationSort = 31;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('section_key', 'insignia-cofradia');
    }

    public static function form(Schema $schema): Schema
    {
        return PatrimonioItemForm::configure($schema, 'insignia-cofradia');
    }

    public static function table(Table $table): Table
    {
        return PatrimonioItemsTable::configure($table, 'insignia-cofradia');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatrimonioInsigniaItems::route('/'),
            'create' => CreatePatrimonioInsigniaItem::route('/create'),
            'edit' => EditPatrimonioInsigniaItem::route('/{record}/edit'),
        ];
    }
}
