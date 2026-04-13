<?php

namespace App\Filament\Resources\PatrimonioItems\Pages;

use App\Filament\Resources\PatrimonioItems\PatrimonioItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatrimonioItems extends ListRecords
{
    protected static string $resource = PatrimonioItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
