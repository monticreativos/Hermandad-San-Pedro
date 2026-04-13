<?php

namespace App\Filament\Resources\PatrimonioPages\Pages;

use App\Filament\Resources\PatrimonioPages\PatrimonioPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatrimonioPage extends CreateRecord
{
    protected static string $resource = PatrimonioPageResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! in_array($data['key'] ?? '', ['paso-cristo-perdon', 'paso-virgen-salud'], true)) {
            unset($data['gallery']);
        }

        return $data;
    }
}
