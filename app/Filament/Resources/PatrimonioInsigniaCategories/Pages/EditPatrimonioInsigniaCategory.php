<?php

namespace App\Filament\Resources\PatrimonioInsigniaCategories\Pages;

use App\Filament\Resources\PatrimonioInsigniaCategories\PatrimonioInsigniaCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPatrimonioInsigniaCategory extends EditRecord
{
    protected static string $resource = PatrimonioInsigniaCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
