<?php

namespace Database\Seeders;

use App\Models\CultosPage;
use App\Models\PenitenciaItinerary;
use Illuminate\Database\Seeder;

class CultosPagesSeeder extends Seeder
{
    public function run(): void
    {
        $estacionPenitenciaCofradiaContent = require __DIR__.'/data/estacion_penitencia_cofradia_content.php';
        $cultosInternosDetallados = require __DIR__.'/content/cultos_internos/_manifest.php';
        $cultosExternosDetallados = require __DIR__.'/content/cultos_externos/_manifest.php';

        $cultosPages = [
            [
                'key' => 'internos',
                'title' => [
                    'es' => 'Cultos internos',
                    'en' => 'Internal worship',
                ],
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
            ],
            [
                'key' => 'externos',
                'title' => [
                    'es' => 'Cultos externos',
                    'en' => 'External worship',
                ],
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
            ],
            [
                'key' => 'estacion-penitencia-cofradia',
                'title' => [
                    'es' => 'Estación de penitencia — Cofradía',
                    'en' => 'Penitential station — Brotherhood',
                ],
                'content' => [
                    'es' => $estacionPenitenciaCofradiaContent['es'],
                    'en' => $estacionPenitenciaCofradiaContent['en'],
                ],
            ],
            [
                'key' => 'estacion-penitencia-horario',
                'title' => [
                    'es' => 'Estación de penitencia — Horario y recorrido',
                    'en' => 'Penitential station — Schedule and route',
                ],
                'content' => [
                    'es' => <<<'HTML'
<p>A continuación se muestra el <strong>itinerario y los horarios previstos</strong> (cruz guía, paso de misterio y paso de palio) para la estación de penitencia. Los datos se gestionan en el panel: menú lateral <strong>Cultos → Itinerarios (Est. penitencia)</strong>; puede crear una entrada por año, ordenar las paradas y marcar los <em>hitos</em> (salida, carrera oficial, recogida, etc.).</p>
<p>Este texto introductorio se edita en <strong>Páginas Cultos → Estación de penitencia — Horario y recorrido</strong>.</p>
HTML,
                    'en' => <<<'HTML'
<p>Below you will find the <strong>itinerary and scheduled times</strong> (guide cross, mystery float and palio float) for the penitential station. Data is managed in the admin sidebar under <strong>Cultos → Itinerarios (Est. penitencia)</strong>: create one record per year, reorder stops and mark <em>milestones</em> (departure, official route, return, etc.).</p>
<p>Edit this introduction under <strong>Cultos pages → Penitential station — Schedule and route</strong>.</p>
HTML,
                ],
            ],
            [
                'key' => 'corpus-christi',
                'title' => [
                    'es' => 'Procesión del Corpus Christi',
                    'en' => 'Corpus Christi procession',
                ],
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
            ],
        ];

        $cultosPages = array_merge($cultosPages, $cultosInternosDetallados, $cultosExternosDetallados);

        foreach ($cultosPages as $page) {
            CultosPage::query()->updateOrCreate(
                ['key' => $page['key']],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                ],
            );
        }

        PenitenciaItinerary::query()->updateOrCreate(
            ['year' => 2026],
            [
                'title' => [
                    'es' => 'Itinerario para la estación de penitencia de 2026',
                    'en' => 'Itinerary for the 2026 penitential station',
                ],
                'stops' => [
                    ['location_label' => 'SALIDA', 'time_cruz_guia' => '18:30', 'time_misterio' => '18:40', 'time_palio' => '18:55', 'is_milestone' => true],
                    ['location_label' => 'ANTONIO MAURA', 'time_cruz_guia' => '19:05', 'time_misterio' => '19:15', 'time_palio' => '19:30', 'is_milestone' => false],
                    ['location_label' => 'CRESPO', 'time_cruz_guia' => '19:35', 'time_misterio' => '19:45', 'time_palio' => '20:00', 'is_milestone' => false],
                    ['location_label' => 'BALMES', 'time_cruz_guia' => '19:55', 'time_misterio' => '20:05', 'time_palio' => '20:20', 'is_milestone' => false],
                    ['location_label' => 'QUEVEDO', 'time_cruz_guia' => '20:15', 'time_misterio' => '20:25', 'time_palio' => '20:40', 'is_milestone' => false],
                    ['location_label' => 'AURORA', 'time_cruz_guia' => '20:35', 'time_misterio' => '20:45', 'time_palio' => '20:55', 'is_milestone' => false],
                    ['location_label' => 'CARRERA OFICIAL', 'time_cruz_guia' => '22:00', 'time_misterio' => '22:10', 'time_palio' => '22:20', 'is_milestone' => true],
                    ['location_label' => 'RECOGIDA', 'time_cruz_guia' => '00:30', 'time_misterio' => '00:40', 'time_palio' => '00:50', 'is_milestone' => true],
                ],
            ],
        );
    }
}
