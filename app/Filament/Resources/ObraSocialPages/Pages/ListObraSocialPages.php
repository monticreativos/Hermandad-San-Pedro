<?php

namespace App\Filament\Resources\ObraSocialPages\Pages;

use App\Filament\Resources\ObraSocialPages\ObraSocialPageResource;
use App\Models\ObraSocialPage;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListObraSocialPages extends ListRecords
{
    protected static string $resource = ObraSocialPageResource::class;

    protected function getHeaderActions(): array
    {
        if (ObraSocialPage::query()->count() >= 3) {
            return [];
        }

        return [
            CreateAction::make(),
        ];
    }
}
