<?php

namespace App\Filament\Resources\PatrimonioItemCategories\Pages;

use App\Filament\Resources\PatrimonioItemCategories\PatrimonioItemCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatrimonioItemCategories extends ListRecords
{
    protected static string $resource = PatrimonioItemCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
