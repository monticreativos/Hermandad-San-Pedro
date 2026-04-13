<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $missingNews = max(0, 3 - News::query()->count());
        if ($missingNews > 0) {
            News::factory()->count($missingNews)->create();
        }

        $brotherhoodNews = [
            [
                'slug' => 'cultos-preparatorios-semana-santa',
                'title' => [
                    'es' => 'Comienzan los cultos preparatorios de Semana Santa',
                    'en' => 'Preparatory worship for Holy Week begins',
                ],
                'content' => [
                    'es' => 'La hermandad convoca a todos los hermanos al triduo preparatorio en nuestra sede canónica durante esta semana.',
                    'en' => 'The brotherhood invites all members to the preparatory triduum at our canonical seat this week.',
                ],
                'related_topics' => [
                    'es' => ['Cultos', 'Semana Santa', 'Hermandad'],
                    'en' => ['Worship', 'Holy Week', 'Brotherhood'],
                ],
                'image_path' => 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'slug' => 'igualacion-y-ensayo-costaleros',
                'title' => [
                    'es' => 'Igualá y primer ensayo de costaleros',
                    'en' => 'First costaleros rehearsal and lineup',
                ],
                'content' => [
                    'es' => 'El cuerpo de capataces anuncia convocatoria para igualá general y posterior ensayo de la cuadrilla.',
                    'en' => 'The foremen team announces the general lineup and first rehearsal for the bearer crew.',
                ],
                'related_topics' => [
                    'es' => ['Costaleros', 'Ensayo', 'Capataces'],
                    'en' => ['Bearers', 'Rehearsal', 'Foremen'],
                ],
                'image_path' => 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'slug' => 'cabildo-general-ordinario',
                'title' => [
                    'es' => 'Cabildo general ordinario de hermanos',
                    'en' => 'Ordinary general chapter of members',
                ],
                'content' => [
                    'es' => 'Se abre el plazo para asistencia al cabildo general ordinario, donde se presentarán cuentas y memoria anual.',
                    'en' => 'Registration opens for the ordinary chapter where annual reports and finances will be presented.',
                ],
                'related_topics' => [
                    'es' => ['Cabildo', 'Hermanos', 'Gestion'],
                    'en' => ['Assembly', 'Members', 'Management'],
                ],
                'image_path' => 'https://images.unsplash.com/photo-1504198266285-165a1d59fdda?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'slug' => 'campana-solidaria-navidad',
                'title' => [
                    'es' => 'Campaña solidaria de Navidad de la hermandad',
                    'en' => 'Brotherhood Christmas solidarity campaign',
                ],
                'content' => [
                    'es' => 'La bolsa de caridad impulsa una recogida de alimentos y productos de higiene para familias del barrio.',
                    'en' => 'Our charity group launches a food and hygiene collection for local families.',
                ],
                'related_topics' => [
                    'es' => ['Caridad', 'Navidad', 'Barrio'],
                    'en' => ['Charity', 'Christmas', 'Neighborhood'],
                ],
                'image_path' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'slug' => 'presentacion-cartel-salida-procesional',
                'title' => [
                    'es' => 'Presentado el cartel de la salida procesional',
                    'en' => 'Official poster for the procession unveiled',
                ],
                'content' => [
                    'es' => 'El salón de actos acogió la presentación oficial del cartel y del programa de actos previos.',
                    'en' => 'The assembly hall hosted the official unveiling of the poster and previous events program.',
                ],
                'related_topics' => [
                    'es' => ['Cartel', 'Salida procesional', 'Actos'],
                    'en' => ['Poster', 'Procession', 'Events'],
                ],
                'image_path' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'slug' => 'concierto-benefico-banda-municipal',
                'title' => [
                    'es' => 'Concierto benéfico junto a la banda municipal',
                    'en' => 'Charity concert with the municipal band',
                ],
                'content' => [
                    'es' => 'La hermandad celebrará un concierto benéfico cuya recaudación irá destinada a la obra social.',
                    'en' => 'The brotherhood will host a charity concert to support social aid projects.',
                ],
                'related_topics' => [
                    'es' => ['Concierto', 'Banda', 'Obra social'],
                    'en' => ['Concert', 'Band', 'Social aid'],
                ],
                'image_path' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        foreach ($brotherhoodNews as $newsItem) {
            News::query()->updateOrCreate(
                ['slug' => $newsItem['slug']],
                [
                    'title' => $newsItem['title'],
                    'content' => $newsItem['content'],
                    'related_topics' => $newsItem['related_topics'],
                    'image_path' => $newsItem['image_path'],
                    'is_published' => true,
                ],
            );
        }
    }
}
