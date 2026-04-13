<?php

use App\Models\WebSetting;
use Illuminate\Database\Migrations\Migration;

/**
 * Sustituye en web_settings los hijos de «Cultos internos» por las claves del Capítulo IV (arts. 17–22).
 */
return new class extends Migration
{
    /**
     * @return list<array{key: string, label_es: string, label_en: string, is_active: bool, sort: int}>
     */
    private function internosChildren(): array
    {
        return [
            [
                'key' => 'culto-misa-corporativa',
                'label_es' => 'Misa corporativa mensual (Art. 17)',
                'label_en' => 'Monthly corporate Mass (Art. 17)',
                'is_active' => true,
                'sort' => 1,
            ],
            [
                'key' => 'culto-triduo-cuaresma-titulares',
                'label_es' => 'Triduo de Cuaresma a los Titulares (Art. 18)',
                'label_en' => 'Lenten triduum to Titular Images (Art. 18)',
                'is_active' => true,
                'sort' => 2,
            ],
            [
                'key' => 'culto-via-crucis-cuaresma',
                'label_es' => 'Vía Crucis en la feligresía (Art. 19)',
                'label_en' => 'Stations of the Cross in the parish (Art. 19)',
                'is_active' => true,
                'sort' => 3,
            ],
            [
                'key' => 'culto-cristo-rey-protestacion-fe',
                'label_es' => 'Cristo Rey — Función e Instituto (Art. 20)',
                'label_en' => 'Christ the King — Principal function (Art. 20)',
                'is_active' => true,
                'sort' => 4,
            ],
            [
                'key' => 'culto-virgen-salud-septiembre',
                'label_es' => 'Virgen de la Salud — Septiembre (Art. 21)',
                'label_en' => 'Our Lady of Health — September (Art. 21)',
                'is_active' => true,
                'sort' => 5,
            ],
            [
                'key' => 'culto-san-pedro-apostol',
                'label_es' => 'San Pedro Apóstol — 29 de junio (Art. 22)',
                'label_en' => 'Saint Peter — 29 June (Art. 22)',
                'is_active' => true,
                'sort' => 6,
            ],
        ];
    }

    public function up(): void
    {
        $setting = WebSetting::query()->first();
        if (! $setting || ! is_array($setting->cultos_submenu_items)) {
            return;
        }

        $items = $setting->cultos_submenu_items;
        foreach ($items as $i => $item) {
            if (($item['key'] ?? '') !== 'internos') {
                continue;
            }
            $items[$i]['children'] = $this->internosChildren();
            $setting->cultos_submenu_items = $items;
            $setting->save();

            return;
        }
    }

    public function down(): void
    {
        // Sin restaurar el menú antiguo (claves ya no válidas en rutas).
    }
};
