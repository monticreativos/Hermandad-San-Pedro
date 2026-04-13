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
            $table->json('obra_social_submenu_items')->nullable()->after('patrimonio_submenu_items');
        });

        $default = [
            [
                'key' => 'labor-asistencial',
                'label_es' => 'Labor Asistencial',
                'label_en' => 'Assistance work',
                'is_active' => true,
                'sort' => 1,
            ],
            [
                'key' => 'diputacion-caridad',
                'label_es' => 'Diputación de Caridad',
                'label_en' => 'Charity board',
                'is_active' => true,
                'sort' => 2,
            ],
            [
                'key' => 'obra-asistencial',
                'label_es' => 'Obra asistencial',
                'label_en' => 'Charitable work',
                'is_active' => true,
                'sort' => 3,
            ],
        ];

        DB::table('web_settings')->whereNull('obra_social_submenu_items')->update([
            'obra_social_submenu_items' => json_encode($default),
        ]);

        foreach (WebSetting::query()->cursor() as $setting) {
            $menu = $setting->menu_items;
            if (! is_array($menu)) {
                continue;
            }
            $routeNames = array_column($menu, 'route_name');
            if (in_array('obra_social.nav', $routeNames, true)) {
                continue;
            }

            $out = [];
            $inserted = false;
            foreach ($menu as $row) {
                if (($row['route_name'] ?? '') === 'contact.index' && ! $inserted) {
                    $out[] = [
                        'route_name' => 'obra_social.nav',
                        'label_es' => 'Obra Social',
                        'label_en' => 'Social outreach',
                        'is_active' => true,
                        'sort' => 7,
                    ];
                    $inserted = true;
                    $row['sort'] = 8;
                }
                $out[] = $row;
            }

            if (! $inserted) {
                $out[] = [
                    'route_name' => 'obra_social.nav',
                    'label_es' => 'Obra Social',
                    'label_en' => 'Social outreach',
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
            $table->dropColumn('obra_social_submenu_items');
        });
    }
};
