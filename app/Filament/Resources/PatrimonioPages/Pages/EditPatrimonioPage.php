<?php

namespace App\Filament\Resources\PatrimonioPages\Pages;

use App\Filament\Resources\PatrimonioPages\PatrimonioPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPatrimonioPage extends EditRecord
{
    protected static string $resource = PatrimonioPageResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (in_array($data['key'] ?? '', ['paso-cristo-perdon', 'paso-virgen-salud'], true)) {
            $data['gallery'] = $data['gallery'] ?? [];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! in_array($data['key'] ?? '', ['paso-cristo-perdon', 'paso-virgen-salud'], true)) {
            unset($data['gallery']);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
