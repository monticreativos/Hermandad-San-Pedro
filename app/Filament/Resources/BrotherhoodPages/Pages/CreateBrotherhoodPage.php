<?php

namespace App\Filament\Resources\BrotherhoodPages\Pages;

use App\Filament\Resources\BrotherhoodPages\BrotherhoodPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBrotherhoodPage extends CreateRecord
{
    protected static string $resource = BrotherhoodPageResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['key'] ?? '') !== 'reglas-reglamentos') {
            unset($data['legal_documents']);
        }
        if (($data['key'] ?? '') !== 'junta-gobierno') {
            unset($data['government_board']);
        }

        return $data;
    }
}
