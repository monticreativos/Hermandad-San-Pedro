<?php

namespace App\Filament\Resources\PenitenciaItineraries;

use App\Filament\Resources\PenitenciaItineraries\Pages\CreatePenitenciaItinerary;
use App\Filament\Resources\PenitenciaItineraries\Pages\EditPenitenciaItinerary;
use App\Filament\Resources\PenitenciaItineraries\Pages\ListPenitenciaItineraries;
use App\Filament\Resources\PenitenciaItineraries\Schemas\PenitenciaItineraryForm;
use App\Filament\Resources\PenitenciaItineraries\Tables\PenitenciaItinerariesTable;
use App\Models\PenitenciaItinerary;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PenitenciaItineraryResource extends Resource
{
    protected static ?string $model = PenitenciaItinerary::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $navigationLabel = 'Itinerarios (Est. penitencia)';

    protected static ?string $modelLabel = 'Itinerario';

    protected static ?string $pluralModelLabel = 'Itinerarios';

    protected static string|UnitEnum|null $navigationGroup = 'Cultos';

    protected static ?int $navigationSort = 27;

    public static function form(Schema $schema): Schema
    {
        return PenitenciaItineraryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PenitenciaItinerariesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPenitenciaItineraries::route('/'),
            'create' => CreatePenitenciaItinerary::route('/create'),
            'edit' => EditPenitenciaItinerary::route('/{record}/edit'),
        ];
    }
}
