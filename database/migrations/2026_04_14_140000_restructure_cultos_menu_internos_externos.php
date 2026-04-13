<?php

use App\Models\CultosPage;
use App\Models\WebSetting;
use Illuminate\Database\Migrations\Migration;

/**
 * Menú Cultos: sin «(Art. …)» en etiquetas; Vía Crucis y Virgen septiembre bajo externos; nueva página Virgen en mayo.
 */
return new class extends Migration
{
    /**
     * @return list<array<string, mixed>>
     */
    private function internosMenuChildren(): array
    {
        return [
            [
                'key' => 'culto-misa-corporativa',
                'label_es' => 'Misa corporativa mensual',
                'label_en' => 'Monthly corporate Mass',
                'is_active' => true,
                'sort' => 1,
            ],
            [
                'key' => 'culto-triduo-cuaresma-titulares',
                'label_es' => 'Triduo de Cuaresma a los Titulares',
                'label_en' => 'Lenten triduum to Titular Images',
                'is_active' => true,
                'sort' => 2,
            ],
            [
                'key' => 'culto-cristo-rey-protestacion-fe',
                'label_es' => 'Cristo Rey — Función e Instituto',
                'label_en' => 'Christ the King — Principal function',
                'is_active' => true,
                'sort' => 3,
            ],
            [
                'key' => 'culto-san-pedro-apostol',
                'label_es' => 'San Pedro Apóstol — 29 de junio',
                'label_en' => 'Saint Peter — 29 June',
                'is_active' => true,
                'sort' => 4,
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function externosMenuChildren(): array
    {
        return [
            [
                'key' => '',
                'label_es' => 'Estación de Penitencia',
                'label_en' => 'Penitential station',
                'is_active' => true,
                'sort' => 1,
                'children' => [
                    [
                        'key' => 'estacion-penitencia-cofradia',
                        'label_es' => 'Cofradía',
                        'label_en' => 'Brotherhood',
                        'is_active' => true,
                        'sort' => 1,
                    ],
                    [
                        'key' => 'estacion-penitencia-horario',
                        'label_es' => 'Horario y Recorrido',
                        'label_en' => 'Schedule and route',
                        'is_active' => true,
                        'sort' => 2,
                    ],
                ],
            ],
            [
                'key' => 'culto-via-crucis-cuaresma',
                'label_es' => 'Vía Crucis en la feligresía',
                'label_en' => 'Stations of the Cross in the parish',
                'is_active' => true,
                'sort' => 2,
                'children' => [],
            ],
            [
                'key' => 'culto-virgen-salud-septiembre',
                'label_es' => 'Virgen de la Salud — Septiembre',
                'label_en' => 'Our Lady of Health — September',
                'is_active' => true,
                'sort' => 3,
                'children' => [],
            ],
            [
                'key' => 'culto-virgen-mayo',
                'label_es' => 'Virgen de la Salud — Mes de mayo',
                'label_en' => 'Our Lady of Health — Month of May',
                'is_active' => true,
                'sort' => 4,
                'children' => [],
            ],
            [
                'key' => 'corpus-christi',
                'label_es' => 'Procesión del Corpus Christi',
                'label_en' => 'Corpus Christi procession',
                'is_active' => true,
                'sort' => 5,
                'children' => [],
            ],
        ];
    }

    public function up(): void
    {
        $virgenMayo = require database_path('seeders/content/cultos_externos/01_virgen_mayo.php');
        CultosPage::query()->updateOrCreate(
            ['key' => $virgenMayo['key']],
            [
                'title' => $virgenMayo['title'],
                'content' => $virgenMayo['content'],
            ],
        );

        $manifest = require database_path('seeders/content/cultos_internos/_manifest.php');
        foreach ($manifest as $page) {
            CultosPage::query()->where('key', $page['key'])->update([
                'title' => $page['title'],
                'content' => $page['content'],
            ]);
        }

        CultosPage::query()->where('key', 'internos')->update([
            'content' => [
                'es' => <<<'HTML'
<p>En esta web, <strong>Cultos internos</strong> agrupa las celebraciones que tienen su centro principal en el templo parroquial: <strong>Misa corporativa</strong> (art. 17), <strong>Triduo de Cuaresma</strong> a los Titulares (art. 18), <strong>Cristo Rey</strong> con función de Instituto y protestación de fe (art. 20) y <strong>San Pedro</strong> (art. 22). Cada página cita el Estatuto en <a href="/docs/estatuto.md">/docs/estatuto.md</a>.</p>
<p>Los actos con recorrido por las calles de la feligresía (p. ej. <strong>Vía Crucis</strong> y <strong>Virgen de la Salud en septiembre</strong>, arts. 19 y 21) y el <strong>mes marial de mayo</strong> figuran en <strong>Cultos externos</strong>, acorde a su desarrollo en la vía pública.</p>
<p><em>Horarios: secretaría, boletín y canales oficiales.</em></p>
HTML,
                'en' => <<<'HTML'
<p>Here, <strong>internal worship</strong> lists celebrations centred in the parish church: corporate Mass (Art. 17), Lenten triduum (Art. 18), Christ the King (Art. 20) and Saint Peter (Art. 22). Statute text: <a href="/docs/estatuto.md">/docs/estatuto.md</a>.</p>
<p>Events mainly in the streets (e.g. <strong>Stations of the Cross</strong> and <strong>Our Lady of Health in September</strong>, Arts. 19–21) and <strong>May Marian devotion</strong> are under <strong>external worship</strong>.</p>
<p><em>Schedules: secretariat and official channels.</em></p>
HTML,
            ],
        ]);

        CultosPage::query()->where('key', 'externos')->update([
            'content' => [
                'es' => <<<'HTML'
<p>Los <strong>cultos externos</strong> reúnen aquí la <strong>Estación de Penitencia</strong> (<strong>art. 23</strong>), la <strong>procesión del Corpus</strong>, el <strong>Vía Crucis</strong> por la feligresía (<strong>art. 19</strong>), el culto de la <strong>Virgen de la Salud en septiembre</strong> con procesión y Rosario (<strong>art. 21</strong>) y el <strong>mes de mayo</strong> mariano en honor de la Titular. El <strong>art. 15</strong> del Estatuto exige autorización del Delegado Episcopal (y civil si procede) para actos externos no previstos en el texto.</p>
<p>Texto íntegro: <a href="/docs/estatuto.md">/docs/estatuto.md</a>.</p>
HTML,
                'en' => <<<'HTML'
<p><strong>External worship</strong> includes the Holy Thursday <strong>penitential station</strong> (Art. 23), <strong>Corpus Christi</strong>, <strong>Stations of the Cross</strong> in the parish (Art. 19), <strong>Our Lady of Health in September</strong> with procession (Art. 21) and <strong>May</strong> Marian devotion. <strong>Art. 15</strong> requires authorisation for other external acts.</p>
<p>Full Statutes: <a href="/docs/estatuto.md">/docs/estatuto.md</a>.</p>
HTML,
            ],
        ]);

        $setting = WebSetting::query()->first();
        if (! $setting || ! is_array($setting->cultos_submenu_items)) {
            return;
        }

        $items = $setting->cultos_submenu_items;
        foreach ($items as $i => $item) {
            $k = $item['key'] ?? '';
            if ($k === 'internos') {
                $items[$i]['children'] = $this->internosMenuChildren();
            }
            if ($k === 'externos') {
                $items[$i]['children'] = $this->externosMenuChildren();
            }
        }
        $setting->cultos_submenu_items = $items;
        $setting->save();
    }

    public function down(): void
    {
        // Sin reversión del menú.
    }
};
