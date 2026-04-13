<?php

namespace App\Filament\Resources\BrotherhoodPages\Pages;

use App\Filament\Resources\BrotherhoodPages\BrotherhoodPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBrotherhoodPages extends ListRecords
{
    protected static string $resource = BrotherhoodPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
