<?php

namespace App\Filament\Resources\WebSettings\Pages;

use App\Filament\Resources\WebSettings\Schemas\WebSettingForm;
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

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $tab = WebSettingForm::activeMainTab();

        $tabKeys = [
            'general' => ['brand_name', 'cta_label', 'cta_url', 'shop_url'],
            'menu' => [
                'menu_items',
                'brotherhood_submenu_items',
                'cultos_submenu_items',
                'patrimonio_submenu_items',
                'obra_social_submenu_items',
            ],
            'slider' => ['hero_slides'],
            'chapel' => ['chapel_card_title', 'chapel_blocks', 'chapel_footer'],
            'donation' => ['donation_card_title', 'donation_blocks', 'donation_footer'],
        ];

        $allowed = $tabKeys[$tab] ?? [];
        $record = $this->getRecord()->fresh();

        foreach ($record->getFillable() as $key) {
            if (! in_array($key, $allowed, true)) {
                $data[$key] = $record->getAttribute($key);
            }
        }

        return parent::mutateFormDataBeforeSave($data);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
