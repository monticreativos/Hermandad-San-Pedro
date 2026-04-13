<?php

namespace App\Filament\Resources\PenitenciaItineraries\Pages;

use App\Filament\Resources\PenitenciaItineraries\PenitenciaItineraryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPenitenciaItinerary extends EditRecord
{
    protected static string $resource = PenitenciaItineraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
