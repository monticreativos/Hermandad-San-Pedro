<?php

namespace App\Filament\Resources\PatrimonioInsigniaItems\Pages;

use App\Filament\Resources\PatrimonioInsigniaItems\PatrimonioInsigniaItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPatrimonioInsigniaItem extends EditRecord
{
    protected static string $resource = PatrimonioInsigniaItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
