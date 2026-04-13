<?php

namespace App\Filament\Resources\PatrimonioItemCategories\Pages;

use App\Filament\Resources\PatrimonioItemCategories\PatrimonioItemCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPatrimonioItemCategory extends EditRecord
{
    protected static string $resource = PatrimonioItemCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
