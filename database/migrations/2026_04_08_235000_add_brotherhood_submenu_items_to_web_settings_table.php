<?php

use App\Models\WebSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->json('brotherhood_submenu_items')->nullable()->after('menu_items');
        });

        $default = [
            [
                'key' => 'fines',
                'label_es' => 'Fines de la Hermandad',
                'label_en' => 'Purposes of the Brotherhood',
                'is_active' => true,
                'sort' => 1,
            ],
            [
                'key' => 'historia',
                'label_es' => 'Historia de la hermandad',
                'label_en' => 'History of the Brotherhood',
                'is_active' => true,
                'sort' => 2,
            ],
            [
                'key' => 'heraldica-simbolos',
                'label_es' => 'Heraldica y Simbolos de la Hermandad',
                'label_en' => 'Heraldry and Symbols',
                'is_active' => true,
                'sort' => 3,
            ],
            [
                'key' => 'reglas-reglamentos',
                'label_es' => 'Reglas y Reglamentos',
                'label_en' => 'Rules and Regulations',
                'is_active' => true,
                'sort' => 4,
            ],
            [
                'key' => 'junta-gobierno',
                'label_es' => 'Junta de Gobierno',
                'label_en' => 'Governing Board',
                'is_active' => true,
                'sort' => 5,
            ],
        ];

        DB::table('web_settings')->whereNull('brotherhood_submenu_items')->update([
            'brotherhood_submenu_items' => json_encode($default),
        ]);

        foreach (WebSetting::query()->cursor() as $setting) {
            $items = $setting->menu_items;
            if (! is_array($items)) {
                continue;
            }
            $filtered = array_values(array_filter(
                $items,
                fn (array $row): bool => ($row['route_name'] ?? '') !== 'history.index',
            ));
            if (count($filtered) !== count($items)) {
                $setting->menu_items = $filtered;
                $setting->saveQuietly();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->dropColumn('brotherhood_submenu_items');
        });
    }
};
