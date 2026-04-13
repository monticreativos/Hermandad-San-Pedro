<?php

use App\Models\CultosPage;
use App\Models\PenitenciaItinerary;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Instalaciones que ya tenían cultos_pages antes del seeder ampliado solo incluían internos/externos;
 * cultos.show usa firstOrFail() y devolvía 404 para las nuevas claves.
 */
return new class extends Migration
{
    public function up(): void
    {
        $cofradiaContent = require database_path('seeders/data/estacion_penitencia_cofradia_content.php');

        $pages = [
            [
                'key' => 'estacion-penitencia-cofradia',
                'title' => [
                    'es' => 'Estación de penitencia — Cofradía',
                    'en' => 'Penitential station — Brotherhood',
                ],
                'content' => [
                    'es' => $cofradiaContent['es'],
                    'en' => $cofradiaContent['en'],
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
                    'es' => '<p>Culto eucarístico y procesión del Corpus Christi. <strong>Amplíe el texto desde Cultos en el panel.</strong></p>',
                    'en' => '<p>Eucharistic worship and Corpus Christi procession. <strong>Expand this text from Cultos in the admin.</strong></p>',
                ],
            ],
        ];

        foreach ($pages as $page) {
            CultosPage::query()->firstOrCreate(
                ['key' => $page['key']],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                ],
            );
        }

        if (Schema::hasTable('penitencia_itineraries')) {
            PenitenciaItinerary::query()->firstOrCreate(
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

    public function down(): void
    {
        CultosPage::query()->whereIn('key', [
            'estacion-penitencia-cofradia',
            'estacion-penitencia-horario',
            'corpus-christi',
        ])->delete();

        if (Schema::hasTable('penitencia_itineraries')) {
            PenitenciaItinerary::query()->where('year', 2026)->delete();
        }
    }
};
