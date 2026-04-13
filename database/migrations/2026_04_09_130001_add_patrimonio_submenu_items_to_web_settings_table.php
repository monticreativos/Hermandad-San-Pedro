<?php

use App\Models\WebSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->json('patrimonio_submenu_items')->nullable()->after('cultos_submenu_items');
        });

        $default = [
            [
                'key' => 'enseres',
                'label_es' => 'Enseres',
                'label_en' => 'Furnishings',
                'is_active' => true,
                'sort' => 1,
            ],
            [
                'key' => 'insignia-cofradia',
                'label_es' => 'Insignia de la Cofradía',
                'label_en' => 'Brotherhood insignia',
                'is_active' => true,
                'sort' => 2,
            ],
            [
                'key' => 'paso-cristo-perdon',
                'label_es' => 'Paso Stmo. Cristo del Perdón',
                'label_en' => 'Float of the Holy Christ of Mercy',
                'is_active' => true,
                'sort' => 3,
            ],
            [
                'key' => 'paso-virgen-salud',
                'label_es' => 'Paso Ntra. Sra. de la Salud',
                'label_en' => 'Float of Our Lady of Health',
                'is_active' => true,
                'sort' => 4,
            ],
        ];

        DB::table('web_settings')->whereNull('patrimonio_submenu_items')->update([
            'patrimonio_submenu_items' => json_encode($default),
        ]);

        foreach (WebSetting::query()->cursor() as $setting) {
            $menu = $setting->menu_items;
            if (! is_array($menu)) {
                continue;
            }
            $routeNames = array_column($menu, 'route_name');
            if (in_array('patrimonio.nav', $routeNames, true)) {
                continue;
            }

            $out = [];
            $inserted = false;
            foreach ($menu as $row) {
                if (($row['route_name'] ?? '') === 'contact.index' && ! $inserted) {
                    $out[] = [
                        'route_name' => 'patrimonio.nav',
                        'label_es' => 'Patrimonio',
                        'label_en' => 'Heritage',
                        'is_active' => true,
                        'sort' => 6,
                    ];
                    $inserted = true;
                    $row['sort'] = 7;
                }
                $out[] = $row;
            }

            if (! $inserted) {
                $out[] = [
                    'route_name' => 'patrimonio.nav',
                    'label_es' => 'Patrimonio',
                    'label_en' => 'Heritage',
                    'is_active' => true,
                    'sort' => 99,
                ];
            }

            $setting->menu_items = $out;
            $setting->saveQuietly();
        }
    }

    public function down(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->dropColumn('patrimonio_submenu_items');
        });
    }
};
