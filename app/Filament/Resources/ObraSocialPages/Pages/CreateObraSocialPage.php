<?php

namespace App\Filament\Resources\ObraSocialPages\Pages;

use App\Filament\Resources\ObraSocialPages\ObraSocialPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateObraSocialPage extends CreateRecord
{
    protected static string $resource = ObraSocialPageResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['key'] ?? '') !== 'diputacion-caridad') {
            unset($data['charity_contact']);
        }

        return $data;
    }
}
