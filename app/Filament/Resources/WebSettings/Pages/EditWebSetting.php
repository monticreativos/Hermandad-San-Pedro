<?php

namespace App\Filament\Resources\WebSettings\Pages;

use App\Filament\Resources\WebSettings\WebSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWebSetting extends EditRecord
{
    protected static string $resource = WebSettingResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['chapel_footer'] = array_merge([
            'label' => ['es' => '', 'en' => ''],
            'link_type' => 'internal',
            'route_name' => 'agenda.index',
            'external_url' => null,
        ], $data['chapel_footer'] ?? []);

        $data['donation_footer'] = array_merge([
            'label' => ['es' => '', 'en' => ''],
            'link_type' => 'internal',
            'route_name' => 'contact.index',
            'external_url' => null,
        ], $data['donation_footer'] ?? []);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
