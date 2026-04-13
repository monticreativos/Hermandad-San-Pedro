<?php

namespace App\Filament\Resources\ObraSocialPages\Pages;

use App\Filament\Resources\ObraSocialPages\ObraSocialPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditObraSocialPage extends EditRecord
{
    protected static string $resource = ObraSocialPageResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (($data['key'] ?? '') === 'diputacion-caridad') {
            $data['charity_contact'] = array_replace_recursive(
                [
                    'person_name' => null,
                    'role' => ['es' => null, 'en' => null],
                    'schedule' => ['es' => null, 'en' => null],
                    'location' => ['es' => null, 'en' => null],
                    'phone' => null,
                    'email' => null,
                ],
                $data['charity_contact'] ?? [],
            );
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['key'] ?? '') !== 'diputacion-caridad') {
            unset($data['charity_contact']);
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
