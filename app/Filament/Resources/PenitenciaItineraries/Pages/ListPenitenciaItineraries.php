<?php

namespace App\Filament\Resources\PenitenciaItineraries\Pages;

use App\Filament\Resources\PenitenciaItineraries\PenitenciaItineraryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPenitenciaItineraries extends ListRecords
{
    protected static string $resource = PenitenciaItineraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
