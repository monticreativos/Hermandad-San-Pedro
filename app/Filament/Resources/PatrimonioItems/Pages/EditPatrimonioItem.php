<?php

namespace App\Filament\Resources\PatrimonioItems\Pages;

use App\Filament\Resources\PatrimonioItems\PatrimonioItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPatrimonioItem extends EditRecord
{
    protected static string $resource = PatrimonioItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
