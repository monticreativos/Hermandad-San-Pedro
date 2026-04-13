<?php

use App\Models\CultosPage;
use Illuminate\Database\Migrations\Migration;

/**
 * Sustituye las claves de cultos internos genéricas por las del Capítulo IV del Estatuto (arts. 17 a 22).
 */
return new class extends Migration
{
    /**
     * @return list<string>
     */
    private function legacyCultoKeys(): array
    {
        return [
            'culto-misa-hermandad',
            'culto-triduo-cristo-perdon',
            'culto-triduo-virgen-salud',
            'culto-cuaresma-preparatorios',
            'culto-besamanos-titulares',
            'culto-funcion-principal',
            'culto-difuntos-fieles',
            'culto-via-crucis-ejercicios',
        ];
    }

    public function up(): void
    {
        CultosPage::query()->whereIn('key', $this->legacyCultoKeys())->delete();

        $pages = require database_path('seeders/content/cultos_internos/_manifest.php');

        foreach ($pages as $page) {
            CultosPage::query()->updateOrCreate(
                ['key' => $page['key']],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                ],
            );
        }

        CultosPage::query()->where('key', 'internos')->update([
            'content' => [
                'es' => <<<'HTML'
<p>Los <strong>cultos internos</strong> previstos en el <strong>Capítulo IV</strong> de los Estatutos (arts. 17 a 22) se celebran en comunión con la Parroquia de San Pedro Apóstol. Desde el menú puede abrir cada culto con su texto tomado del Estatuto y desarrollo pastoral.</p>
<p><em>Horarios y convocatorias concretas: secretaría, boletín y canales oficiales.</em></p>
HTML,
                'en' => <<<'HTML'
<p><strong>Internal worship</strong> under <strong>Chapter IV</strong> of the Statutes (Arts. 17–22) is listed in the menu with statute-based texts.</p>
<p><em>Schedules: secretariat and official channels.</em></p>
HTML,
            ],
        ]);
    }

    public function down(): void
    {
        $manifest = require database_path('seeders/content/cultos_internos/_manifest.php');
        $keys = array_map(fn (array $p): string => $p['key'], $manifest);

        CultosPage::query()->whereIn('key', $keys)->delete();
    }
};
