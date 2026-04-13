<?php

use App\Models\BrotherhoodPage;
use App\Models\CultosPage;
use Illuminate\Database\Migrations\Migration;

/**
 * Actualiza textos de cultos y reglas para alinearlos con public/docs/estatuto.md como fuente normativa publicada.
 */
return new class extends Migration
{
    public function up(): void
    {
        $manifest = require database_path('seeders/content/cultos_internos/_manifest.php');
        foreach ($manifest as $page) {
            CultosPage::query()->where('key', $page['key'])->update([
                'title' => $page['title'],
                'content' => $page['content'],
            ]);
        }

        $estacion = require database_path('seeders/data/estacion_penitencia_cofradia_content.php');
        CultosPage::query()->where('key', 'estacion-penitencia-cofradia')->update([
            'content' => $estacion,
        ]);

        CultosPage::query()->where('key', 'internos')->update([
            'content' => [
                'es' => <<<'HTML'
<p>Los <strong>cultos internos</strong> previstos en el <strong>Capítulo IV</strong> del Estatuto (arts. 17 a 22) se celebran en comunión con la Parroquia de San Pedro Apóstol. Desde el menú puede abrir cada culto: la cita legal reproduce el texto de <a href="/docs/estatuto.md">/docs/estatuto.md</a> y el desarrollo es pastoral.</p>
<p><em>Horarios y convocatorias concretas: secretaría, boletín y canales oficiales.</em></p>
HTML,
                'en' => <<<'HTML'
<p><strong>Internal worship</strong> under <strong>Chapter IV</strong> of the Statutes (Arts. 17–22) is listed in the menu. Legal quotations follow the Spanish text in <a href="/docs/estatuto.md">/docs/estatuto.md</a>.</p>
<p><em>Schedules: secretariat and official channels.</em></p>
HTML,
            ],
        ]);

        CultosPage::query()->where('key', 'corpus-christi')->update([
            'content' => [
                'es' => <<<'HTML'
<p>Culto eucarístico y procesión del Corpus Christi como culto externo de la Hermandad. La convocatoria y autorizaciones se ajustan al <strong>art. 15</strong> del Estatuto cuando el acto no esté expresamente detallado en el Capítulo IV; el texto íntegro está en <a href="/docs/estatuto.md">/docs/estatuto.md</a>.</p>
<p><strong>Amplíe el texto desde Cultos en el panel.</strong></p>
HTML,
                'en' => <<<'HTML'
<p>Eucharistic worship and Corpus Christi procession as external brotherhood worship. Authorisations follow <strong>Art. 15</strong> of the Statutes when not expressly listed in Chapter IV; full text: <a href="/docs/estatuto.md">/docs/estatuto.md</a>.</p>
<p><strong>Expand this text from Cultos in the admin.</strong></p>
HTML,
            ],
        ]);

        CultosPage::query()->where('key', 'externos')->update([
            'content' => [
                'es' => <<<'HTML'
<p>Los <strong>cultos externos</strong> incluyen, entre otros, la <strong>Estación de Penitencia</strong> del Jueves Santo (<strong>art. 23</strong> del Capítulo IV) y la participación en celebraciones fuera del templo. El <strong>art. 15</strong> del Estatuto dispone que los actos externos no previstos en el texto normativo requieren autorización del Delegado Episcopal (y la civil si procede).</p>
<p>El texto completo del Estatuto está en <a href="/docs/estatuto.md">/docs/estatuto.md</a>. Use el submenú para la estación de penitencia, itinerario y demás cultos externos publicados.</p>
HTML,
                'en' => <<<'HTML'
<p><strong>External worship</strong> includes, among others, the Holy Thursday <strong>penitential station</strong> (<strong>Art. 23</strong>, Chapter IV) and liturgical acts outside the church. <strong>Art. 15</strong> requires diocesan authorisation (and civil permits when needed) for external acts not listed in the Statutes.</p>
<p>Full Spanish Statutes: <a href="/docs/estatuto.md">/docs/estatuto.md</a>. Use the submenu for the penitential station, route and other published external worship.</p>
HTML,
            ],
        ]);

        $reglas = require database_path('seeders/content/brotherhood_reglas.php');
        BrotherhoodPage::query()->where('key', 'reglas-reglamentos')->update([
            'title' => $reglas['title'],
            'content' => $reglas['content'],
        ]);
    }

    public function down(): void
    {
        // Irreversible: no se restauran versiones anteriores del contenido.
    }
};
