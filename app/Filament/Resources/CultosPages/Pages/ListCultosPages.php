<?php

namespace App\Filament\Resources\CultosPages\Pages;

use App\Filament\Resources\CultosPages\CultosPageResource;
use App\Models\CultosPage;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCultosPages extends ListRecords
{
    protected static string $resource = CultosPageResource::class;

    protected function getHeaderActions(): array
    {
        return CultosPage::query()->count() >= 20
            ? []
            : [CreateAction::make()];
    }
}
