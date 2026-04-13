<?php

namespace App\Filament\Resources\PatrimonioInsigniaCategories\Pages;

use App\Filament\Resources\PatrimonioInsigniaCategories\PatrimonioInsigniaCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatrimonioInsigniaCategories extends ListRecords
{
    protected static string $resource = PatrimonioInsigniaCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
