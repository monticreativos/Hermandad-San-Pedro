<?php

namespace Database\Seeders;

use App\Models\BrotherhoodPage;
use App\Models\CultosPage;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\News;
use App\Models\ObraSocialPage;
use App\Models\PatrimonioItem;
use App\Models\PatrimonioItemCategory;
use App\Models\PatrimonioPage;
use App\Models\PenitenciaItinerary;
use App\Models\User;
use App\Models\WebSetting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::query()->updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $missingNews = max(0, 3 - News::query()->count());
        $missingEvents = max(0, 3 - Event::query()->count());

        if ($missingNews > 0) {
            News::factory()->count($missingNews)->create();
        }

        if ($missingEvents > 0) {
            Event::factory()->count($missingEvents)->create();
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

        $brotherhoodPages = [
            [
                'key' => 'fines',
                'title' => [
                    'es' => 'Fines de la Hermandad',
                    'en' => 'Purposes of the Brotherhood',
                ],
                'content' => [
                    'es' => <<<'HTML'
<h2>Identidad y misión</h2>
<p>La Hermandad de San Pedro se define, ante todo, como una corporación religiosa en comunión con la Iglesia diocesana, arraigada en la vida parroquial del barrio que le da nombre. Los promotores que en los años ochenta impulsaron su constitución concibieron una organización capaz de reunir <strong>culto público, formación cristiana, acción social y actividad cultural</strong>, de modo que la fe se traduzca en servicio cotidiano a las personas y al territorio.</p>
<h2>Culto y devoción a los Titulares</h2>
<p>El <strong>fin primordial</strong> de la hermandad es honrar a sus <strong>Sagrados Titulares</strong>, en la línea tradicional de la piedad cofrade: celebrar con dignidad la liturgia, promover la oración personal y comunitaria, y preparar espiritualmente a los cofrades para vivir con coherencia el misterio pascual. La devoción se expresa en el cuidado del patrimonio religioso vinculado al culto, en los cultos extraordinarios y en la <strong>estación de penitencia</strong>, cuando las normas eclesiásticas lo permiten, con el <strong>recogimiento, la austeridad y el sentido penitencial</strong> que caracterizan la identidad penitencial de la corporación.</p>
<h2>Finalidad social y cultural en el barrio</h2>
<p>En continuidad con el impulso fundacional —que situó en el centro el acercamiento de los vecinos al templo y la mejora del tejido asociativo—, la hermandad desarrolla <strong>obras de caridad y solidaridad</strong>, colabora con iniciativas parroquiales y apoya a quienes se encuentran en situación de necesidad, en sintonía con entidades como <strong>Cáritas</strong> y con la sensibilidad social propia del Evangelio. Asimismo fomenta actividades culturales, la difusión de la historia local y cofrade, y la participación de familias y jóvenes, para que la tradición sea <strong>memoria viva</strong> y no mero recuerdo.</p>
<h2>Vida de hermandad y gobierno</h2>
<p>Los cofrades se comprometen a conocer y observar los <strong>Estatutos y Reglamentos</strong>, a participar responsablemente en la vida interna y a colaborar con la <strong>Junta de Gobierno</strong> en la administración transparente de los bienes destinados al culto y a la obra social. La formación permanente, el respeto mutuo y la lealtad al párroco y a las directrices pastorales garantizan que estos fines se renueven en cada generación, siempre al servicio de <strong>Dios, la Iglesia y el barrio de San Pedro</strong>.</p>
HTML,
                    'en' => <<<'HTML'
<h2>Identity and mission</h2>
<p>The Brotherhood of San Pedro is, first and foremost, a religious corporation in communion with the diocesan Church, rooted in the parish life of the neighbourhood that gives it its name. Those who promoted its foundation in the 1980s envisioned an organisation that could bring together <strong>public worship, Christian formation, social outreach and cultural activity</strong>, so that faith becomes practical service to people and to the local community.</p>
<h2>Worship and devotion to the Titular Images</h2>
<p>The <strong>primary purpose</strong> of the brotherhood is to honour its <strong>Sacred Titular Images</strong> in the tradition of popular piety: to celebrate the liturgy with dignity, foster personal and communal prayer, and prepare members to live the paschal mystery with integrity. Devotion includes care of the religious heritage linked to worship, special devotions and the <strong>penitential procession</strong> when Church norms allow, marked by <strong>restraint, austerity and a penitential spirit</strong> consistent with the brotherhood’s identity.</p>
<h2>Social and cultural aims in the neighbourhood</h2>
<p>Following the founders’ emphasis on drawing neighbours to the church and strengthening civic association, the brotherhood carries out <strong>works of charity and solidarity</strong>, supports parish initiatives and helps those in need, in harmony with bodies such as <strong>Caritas</strong> and the social demands of the Gospel. It also promotes cultural activities, local and brotherhood history, and the involvement of families and young people so that tradition remains <strong>living memory</strong>, not nostalgia alone.</p>
<h2>Brotherhood life and governance</h2>
<p>Members commit to knowing and observing the <strong>Statutes and Regulations</strong>, to taking part responsibly in internal life and to working with the <strong>Governing Board</strong> in the transparent stewardship of assets intended for worship and charitable work. Ongoing formation, mutual respect and loyalty to the parish priest and pastoral guidelines ensure these aims are renewed in every generation, at the service of <strong>God, the Church and the San Pedro neighbourhood</strong>.</p>
HTML,
                ],
            ],
            array_merge(
                ['key' => 'historia'],
                require __DIR__.'/content/brotherhood_historia.php'
            ),
            array_merge(
                ['key' => 'heraldica-simbolos'],
                require __DIR__.'/content/brotherhood_heraldica.php'
            ),
            array_merge(
                ['key' => 'reglas-reglamentos'],
                require __DIR__.'/content/brotherhood_reglas.php'
            ),
            array_merge(
                ['key' => 'junta-gobierno'],
                require __DIR__.'/content/brotherhood_junta_gobierno.php'
            ),
        ];

        foreach ($brotherhoodPages as $page) {
            $data = [
                'title' => $page['title'],
                'content' => $page['content'],
            ];
            if (array_key_exists('legal_documents', $page)) {
                $data['legal_documents'] = $page['legal_documents'];
            }
            if (array_key_exists('government_board', $page)) {
                $data['government_board'] = $page['government_board'];
            }
            BrotherhoodPage::query()->updateOrCreate(
                ['key' => $page['key']],
                $data,
            );
        }

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

        $patrimonioPages = [
            [
                'key' => 'enseres',
                'title' => [
                    'es' => 'Enseres',
                    'en' => 'Furnishings',
                ],
                'content' => [
                    'es' => <<<'HTML'
<p>En esta sección se recoge el <strong>patrimonio mueble</strong> de la Hermandad: piezas de culto, enseres procesionales, orfebrería, textil y otros bienes de interés histórico-artístico o devocional. Cada ficha incluye datos descriptivos, autoría o datación cuando se conocen, y una <strong>galería de imágenes</strong> cuando hay material gráfico disponible.</p>
<p>El catálogo se gestiona desde el panel de administración: puede crear <strong>categorías</strong>, ordenar las piezas y ampliar textos o fotografías en cualquier momento.</p>
HTML,
                    'en' => <<<'HTML'
<p>This section presents the brotherhood’s <strong>movable heritage</strong>: pieces used in worship, procession furnishings, metalwork, textiles and other items of historical, artistic or devotional interest. Each entry includes descriptive details, authorship or dating where known, and an <strong>image gallery</strong> when visual material is available.</p>
<p>The catalogue is managed from the administration panel: you can create <strong>categories</strong>, reorder items and extend texts or photographs at any time.</p>
HTML,
                ],
            ],
            [
                'key' => 'insignia-cofradia',
                'title' => [
                    'es' => 'Insignia de la Cofradía',
                    'en' => 'Brotherhood insignia',
                ],
                'content' => [
                    'es' => <<<'HTML'
<p>En esta sección se documentan las <strong>insignias propias de la cofradía</strong>: medallas, cruces, distintivos, medallas de hermano y demás piezas que identifican la pertenencia a la Hermandad o recuerdan a los Titulares. Cada ficha admite <strong>descripción detallada</strong>, datos de autoría o cronología y una <strong>galería de imágenes</strong>.</p>
<p>El catálogo se administra en el panel: <strong>categorías</strong> exclusivas de insignia, orden de las piezas y publicación en la web, igual que en Enseres.</p>
HTML,
                    'en' => <<<'HTML'
<p>This section documents the brotherhood’s <strong>own insignia</strong>: medals, crosses, badges, member medals and other pieces that mark belonging to the Brotherhood or devotion to the Titular Images. Each entry supports a <strong>full description</strong>, authorship or dating and an <strong>image gallery</strong>.</p>
<p>The catalogue is managed in the admin: insignia-only <strong>categories</strong>, display order and web visibility, in the same way as Furnishings.</p>
HTML,
                ],
            ],
            [
                'key' => 'paso-cristo-perdon',
                'title' => [
                    'es' => 'Paso Stmo. Cristo del Perdón',
                    'en' => 'Float of the Holy Christ of Mercy',
                ],
                'content' => [
                    'es' => "El paso procesional del Santisimo Cristo del Perdon.\nHistoria, autores y caracteristicas del conjunto escultorico.",
                    'en' => "The procession float of the Holy Christ of Mercy.\nHistory, artists and features of the sculptural group.",
                ],
            ],
            array_merge(
                ['key' => 'paso-virgen-salud'],
                require __DIR__.'/content/patrimonio_paso_virgen_salud.php'
            ),
        ];

        foreach ($patrimonioPages as $page) {
            $patrimonioData = [
                'title' => $page['title'],
                'content' => $page['content'],
            ];
            if (array_key_exists('gallery', $page)) {
                $patrimonioData['gallery'] = $page['gallery'];
            }
            PatrimonioPage::query()->updateOrCreate(
                ['key' => $page['key']],
                $patrimonioData,
            );
        }

        if (PatrimonioItemCategory::query()->where('section_key', 'enseres')->count() === 0) {
            $cOrfe = PatrimonioItemCategory::query()->create([
                'section_key' => 'enseres',
                'name' => ['es' => 'Orfebrería', 'en' => 'Metalwork'],
                'sort_order' => 1,
            ]);
            PatrimonioItemCategory::query()->create([
                'section_key' => 'enseres',
                'name' => ['es' => 'Textil y insignias', 'en' => 'Textiles and insignia'],
                'sort_order' => 2,
            ]);
            PatrimonioItem::query()->create([
                'section_key' => 'enseres',
                'patrimonio_item_category_id' => $cOrfe->id,
                'name' => [
                    'es' => 'Pieza de ejemplo (sustituir)',
                    'en' => 'Sample piece (replace)',
                ],
                'description' => [
                    'es' => '<p>Ficha de muestra para comprobar el catálogo. Edítela o elimínela desde el admin y añada las piezas reales de la Hermandad.</p>',
                    'en' => '<p>Sample record to preview the catalogue. Edit or delete it from the admin and add the brotherhood’s real pieces.</p>',
                ],
                'year' => '1990',
                'author' => 'Autor desconocido (ejemplo)',
                'gallery' => null,
                'sort_order' => 0,
                'is_published' => true,
            ]);
        }

        if (PatrimonioItemCategory::query()->where('section_key', 'insignia-cofradia')->count() === 0) {
            $cMed = PatrimonioItemCategory::query()->create([
                'section_key' => 'insignia-cofradia',
                'name' => ['es' => 'Medallas', 'en' => 'Medals'],
                'sort_order' => 1,
            ]);
            PatrimonioItemCategory::query()->create([
                'section_key' => 'insignia-cofradia',
                'name' => ['es' => 'Cruces y distintivos', 'en' => 'Crosses and badges'],
                'sort_order' => 2,
            ]);
            PatrimonioItem::query()->create([
                'section_key' => 'insignia-cofradia',
                'patrimonio_item_category_id' => $cMed->id,
                'name' => [
                    'es' => 'Pieza de insignia (ejemplo)',
                    'en' => 'Sample insignia piece',
                ],
                'description' => [
                    'es' => '<p>Ficha de muestra para la sección Insignia. Sustitúyala por las piezas reales desde el admin (Insignia → piezas).</p>',
                    'en' => '<p>Sample entry for the Insignia section. Replace it with real pieces from the admin.</p>',
                ],
                'year' => null,
                'author' => null,
                'gallery' => null,
                'sort_order' => 0,
                'is_published' => true,
            ]);
        }

        $obraSocialPages = [
            array_merge(
                ['key' => 'labor-asistencial'],
                require __DIR__.'/content/obra_social_labor_asistencial.php'
            ),
            array_merge(
                ['key' => 'diputacion-caridad'],
                require __DIR__.'/content/obra_social_diputacion_caridad.php'
            ),
            array_merge(
                ['key' => 'obra-asistencial'],
                require __DIR__.'/content/obra_social_obra_asistencial.php'
            ),
        ];

        foreach ($obraSocialPages as $page) {
            $obraData = [
                'title' => $page['title'],
                'content' => $page['content'],
            ];
            if (array_key_exists('charity_contact', $page)) {
                $obraData['charity_contact'] = $page['charity_contact'];
            }
            ObraSocialPage::query()->updateOrCreate(
                ['key' => $page['key']],
                $obraData,
            );
        }

        WebSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'brand_name' => [
                    'es' => 'Hermandad',
                    'en' => 'Brotherhood',
                ],
                'cta_label' => [
                    'es' => 'Acceder',
                    'en' => 'Login',
                ],
                'cta_url' => '/login',
                'shop_url' => null,
                'menu_items' => [
                    [
                        'route_name' => 'home',
                        'label_es' => 'Inicio',
                        'label_en' => 'Home',
                        'is_active' => true,
                        'sort' => 1,
                    ],
                    [
                        'route_name' => 'news.index',
                        'label_es' => 'Noticias',
                        'label_en' => 'News',
                        'is_active' => true,
                        'sort' => 2,
                    ],
                    [
                        'route_name' => 'agenda.index',
                        'label_es' => 'Agenda',
                        'label_en' => 'Agenda',
                        'is_active' => true,
                        'sort' => 3,
                    ],
                    [
                        'route_name' => 'titulares.index',
                        'label_es' => 'Titulares',
                        'label_en' => 'Titular Images',
                        'is_active' => true,
                        'sort' => 4,
                    ],
                    [
                        'route_name' => 'cultos.nav',
                        'label_es' => 'Cultos',
                        'label_en' => 'Worship',
                        'is_active' => true,
                        'sort' => 5,
                    ],
                    [
                        'route_name' => 'patrimonio.nav',
                        'label_es' => 'Patrimonio',
                        'label_en' => 'Heritage',
                        'is_active' => true,
                        'sort' => 6,
                    ],
                    [
                        'route_name' => 'obra_social.nav',
                        'label_es' => 'Obra Social',
                        'label_en' => 'Social outreach',
                        'is_active' => true,
                        'sort' => 7,
                    ],
                    [
                        'route_name' => 'contact.index',
                        'label_es' => 'Contacto',
                        'label_en' => 'Contact',
                        'is_active' => true,
                        'sort' => 8,
                    ],
                ],
                'brotherhood_submenu_items' => [
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
                        'label_es' => 'Heráldica y símbolos de la Hermandad',
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
                ],
                'cultos_submenu_items' => [
                    [
                        'key' => 'internos',
                        'label_es' => 'Cultos internos',
                        'label_en' => 'Internal worship',
                        'is_active' => true,
                        'sort' => 1,
                        'children' => [
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
                        ],
                    ],
                    [
                        'key' => 'externos',
                        'label_es' => 'Cultos externos',
                        'label_en' => 'External worship',
                        'is_active' => true,
                        'sort' => 2,
                        'children' => [
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
                        ],
                    ],
                ],
                'patrimonio_submenu_items' => [
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
                ],
                'obra_social_submenu_items' => [
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
                ],
                'hero_slides' => [
                    [
                        'image' => 'https://images.unsplash.com/photo-1512632578888-169bbbc64f33?auto=format&fit=crop&w=1600&q=80',
                        'alt_es' => 'Salida procesional de la hermandad',
                        'alt_en' => 'Brotherhood procession',
                        'sort' => 1,
                        'is_active' => true,
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1600&q=80',
                        'alt_es' => 'Detalle patrimonial y ambiente solemne',
                        'alt_en' => 'Heritage and solemn atmosphere',
                        'sort' => 2,
                        'is_active' => true,
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1600&q=80',
                        'alt_es' => 'Comunidad reunida en acto religioso',
                        'alt_en' => 'Community gathered in religious event',
                        'sort' => 3,
                        'is_active' => true,
                    ],
                ],
                'chapel_card_title' => [
                    'es' => 'Capilla y misas',
                    'en' => 'Chapel and Masses',
                ],
                'chapel_blocks' => [
                    [
                        'type' => 'heading',
                        'body_es' => 'De lunes a sabado:',
                        'body_en' => 'Monday to Saturday:',
                    ],
                    [
                        'type' => 'paragraph',
                        'body_es' => "Capilla: De 10 a 13.30 h y de 18 a 21 h.\nMisa a las 20 h.",
                        'body_en' => "Chapel: 10 a.m. to 1:30 p.m. and 6 p.m. to 9 p.m.\nMass at 8 p.m.",
                    ],
                    [
                        'type' => 'heading',
                        'body_es' => 'Domingos',
                        'body_en' => 'Sundays',
                    ],
                    [
                        'type' => 'paragraph',
                        'body_es' => "Capilla: De 10.30 h a 14 h.\nMisa: A las 13 h.",
                        'body_en' => "Chapel: 10:30 a.m. to 2 p.m.\nMass: 1 p.m.",
                    ],
                    [
                        'type' => 'callout',
                        'body_es' => 'Adoracion eucaristica, jueves, de 18 a 19.50 h.',
                        'body_en' => 'Eucharistic adoration, Thursdays, 6 p.m. to 7:50 p.m.',
                    ],
                ],
                'chapel_footer' => [
                    'label' => [
                        'es' => 'Ver todos los cultos',
                        'en' => 'View all worship',
                    ],
                    'link_type' => 'internal',
                    'route_name' => 'agenda.index',
                    'external_url' => null,
                ],
                'donation_card_title' => [
                    'es' => 'Donacion para el proyecto de dorado del paso del Senor',
                    'en' => "Donation for the Lord's float gilding project",
                ],
                'donation_blocks' => [
                    [
                        'type' => 'paragraph',
                        'body_es' => 'Impulsamos la restauracion y el dorado del paso de Nuestro Senor. Cada aportacion, grande o modesta, engrandece nuestro acompanamiento al misterio y deja huella en la historia de la hermandad.',
                        'body_en' => 'We are raising funds for the restoration and gilding of Our Lord\'s processional float. Every contribution, large or small, supports our devotion and becomes part of the brotherhood\'s story.',
                    ],
                    [
                        'type' => 'callout',
                        'body_es' => 'Puede solicitar informacion sobre cuenta dedicada, plazos del proyecto y certificados de donacion en secretaria o a traves del formulario de contacto.',
                        'body_en' => 'You can request details of the dedicated account, project timeline and donation receipts from the office or via the contact form.',
                    ],
                ],
                'donation_footer' => [
                    'label' => [
                        'es' => 'Quiero colaborar',
                        'en' => 'I want to contribute',
                    ],
                    'link_type' => 'internal',
                    'route_name' => 'contact.index',
                    'external_url' => null,
                ],
            ],
        );

        $this->seedAgendaEventsFivePerMonth();
    }

    private function seedAgendaEventsFivePerMonth(): void
    {
        $categoryIdsBySlug = EventCategory::query()->pluck('id', 'slug');
        if ($categoryIdsBySlug->isEmpty()) {
            return;
        }

        $year = (int) now()->format('Y');
        $templates = [
            ['es' => 'Misa de hermandad', 'en' => 'Brotherhood Mass', 'type' => 'cultos'],
            ['es' => 'Triduo y culto solemne', 'en' => 'Triduum and solemn worship', 'type' => 'cultos'],
            ['es' => 'Ensayo de costaleros', 'en' => 'Bearer rehearsal', 'type' => 'ensayo'],
            ['es' => 'Salida procesional (ensayo)', 'en' => 'Processional rehearsal', 'type' => 'salida'],
            ['es' => 'Cabildo de hermanos', 'en' => 'Members chapter', 'type' => 'otros'],
        ];
        $days = [5, 12, 18, 24, 28];

        foreach (range(1, 12) as $month) {
            $count = Event::query()
                ->whereYear('date_time', $year)
                ->whereMonth('date_time', $month)
                ->count();

            if ($count >= 5) {
                continue;
            }

            $dim = (int) Carbon::create($year, $month, 1)->daysInMonth;
            $monthLabelEs = Carbon::create($year, $month, 1)->locale('es')->translatedFormat('F');
            $monthLabelEn = Carbon::create($year, $month, 1)->locale('en')->format('F');

            for ($i = $count; $i < 5; $i++) {
                $dom = min($days[$i], $dim);
                $hour = 18 + ($i % 3);
                $minute = ($i * 17) % 60;
                $at = Carbon::create($year, $month, $dom, $hour, $minute, 0);
                $tpl = $templates[$i];

                while (
                    Event::query()
                        ->where('date_time', $at->format('Y-m-d H:i:s'))
                        ->exists()
                ) {
                    $at->addMinutes(30);
                }

                Event::query()->create([
                    'name' => [
                        'es' => $tpl['es'].' — '.$monthLabelEs,
                        'en' => $tpl['en'].' — '.$monthLabelEn,
                    ],
                    'description' => [
                        'es' => '<p>Acto programado en la agenda de la hermandad.</p>',
                        'en' => '<p>Scheduled brotherhood event.</p>',
                    ],
                    'location' => 'Sede de la hermandad',
                    'date_time' => $at,
                    'event_category_id' => $categoryIdsBySlug[$tpl['type']] ?? $categoryIdsBySlug['otros'],
                    'gallery' => [],
                ]);
            }
        }
    }
}
