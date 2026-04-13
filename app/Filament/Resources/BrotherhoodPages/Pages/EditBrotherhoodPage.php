<?php

namespace App\Filament\Resources\BrotherhoodPages\Pages;

use App\Filament\Resources\BrotherhoodPages\BrotherhoodPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBrotherhoodPage extends EditRecord
{
    protected static string $resource = BrotherhoodPageResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (($data['key'] ?? '') === 'reglas-reglamentos') {
            $data['legal_documents'] = array_merge(
                [
                    'estatutos' => null,
                    'reglamento_interno' => null,
                    'estatuto_base_diocesano' => null,
                ],
                $data['legal_documents'] ?? [],
            );
        }

        if (($data['key'] ?? '') === 'junta-gobierno') {
            $data['government_board'] = array_replace_recursive(
                [
                    'members' => [],
                    'past_mayors' => [],
                ],
                $data['government_board'] ?? [],
            );
            if (! is_array($data['government_board']['members'] ?? null)) {
                $data['government_board']['members'] = [];
            }
            if (! is_array($data['government_board']['past_mayors'] ?? null)) {
                $data['government_board']['past_mayors'] = [];
            }
        }

        return $data;
    }

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

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
