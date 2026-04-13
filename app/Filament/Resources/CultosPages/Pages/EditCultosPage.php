<?php

namespace App\Filament\Resources\CultosPages\Pages;

use App\Filament\Resources\CultosPages\CultosPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCultosPage extends EditRecord
{
    protected static string $resource = CultosPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
