<?php

namespace App\Filament\Resources\PatrimonioInsigniaItems\Pages;

use App\Filament\Resources\PatrimonioInsigniaItems\PatrimonioInsigniaItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatrimonioInsigniaItems extends ListRecords
{
    protected static string $resource = PatrimonioInsigniaItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
