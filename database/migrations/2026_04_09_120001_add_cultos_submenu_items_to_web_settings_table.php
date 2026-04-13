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
            $table->json('cultos_submenu_items')->nullable()->after('brotherhood_submenu_items');
        });

        $default = [
            [
                'key' => 'internos',
                'label_es' => 'Cultos internos',
                'label_en' => 'Internal worship',
                'is_active' => true,
                'sort' => 1,
            ],
            [
                'key' => 'externos',
                'label_es' => 'Cultos externos',
                'label_en' => 'External worship',
                'is_active' => true,
                'sort' => 2,
            ],
        ];

        DB::table('web_settings')->whereNull('cultos_submenu_items')->update([
            'cultos_submenu_items' => json_encode($default),
        ]);

        foreach (WebSetting::query()->cursor() as $setting) {
            $menu = $setting->menu_items;
            if (! is_array($menu)) {
                continue;
            }
            $routeNames = array_column($menu, 'route_name');
            if (in_array('cultos.nav', $routeNames, true)) {
                continue;
            }

            $out = [];
            $inserted = false;
            foreach ($menu as $row) {
                if (($row['route_name'] ?? '') === 'contact.index' && ! $inserted) {
                    $out[] = [
                        'route_name' => 'cultos.nav',
                        'label_es' => 'Cultos',
                        'label_en' => 'Worship',
                        'is_active' => true,
                        'sort' => 5,
                    ];
                    $inserted = true;
                    $row['sort'] = 6;
                }
                $out[] = $row;
            }

            if (! $inserted) {
                $out[] = [
                    'route_name' => 'cultos.nav',
                    'label_es' => 'Cultos',
                    'label_en' => 'Worship',
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
            $table->dropColumn('cultos_submenu_items');
        });
    }
};
