<?php

namespace App\Filament\Resources\WebSettings\Pages;

use App\Filament\Resources\WebSettings\WebSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWebSettings extends ListRecords
{
    protected static string $resource = WebSettingResource::class;

    protected function getHeaderActions(): array
    {
        if (static::getResource()::getEloquentQuery()->count() > 0) {
            return [];
        }

        return [
            CreateAction::make(),
        ];
    }
}
