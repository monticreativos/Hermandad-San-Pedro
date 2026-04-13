<?php

namespace App\Filament\Resources\PatrimonioPages\Pages;

use App\Filament\Resources\PatrimonioPages\PatrimonioPageResource;
use App\Models\PatrimonioPage;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatrimonioPages extends ListRecords
{
    protected static string $resource = PatrimonioPageResource::class;

    protected function getHeaderActions(): array
    {
        if (PatrimonioPage::query()->count() >= 4) {
            return [];
        }

        return [
            CreateAction::make(),
        ];
    }
}
