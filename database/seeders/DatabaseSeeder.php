<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventCategory;
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

        $this->seedDefaultEventCategories();

        $this->call(NewsSeeder::class);

        $this->call(BrotherhoodPagesSeeder::class);

        $this->call(DynamicPagesSeeder::class);

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
                'cta_url' => '/admin',
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
                        'focus_x' => 50,
                        'focus_y' => 50,
                        'focus_zoom' => 100,
                        'sort' => 1,
                        'is_active' => true,
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1600&q=80',
                        'alt_es' => 'Detalle patrimonial y ambiente solemne',
                        'alt_en' => 'Heritage and solemn atmosphere',
                        'focus_x' => 50,
                        'focus_y' => 50,
                        'focus_zoom' => 100,
                        'sort' => 2,
                        'is_active' => true,
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1600&q=80',
                        'alt_es' => 'Comunidad reunida en acto religioso',
                        'alt_en' => 'Community gathered in religious event',
                        'focus_x' => 50,
                        'focus_y' => 50,
                        'focus_zoom' => 100,
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

    private function seedDefaultEventCategories(): void
    {
        $categories = [
            [
                'slug' => 'cultos',
                'name' => ['es' => 'Cultos', 'en' => 'Worship'],
                'color' => '#7c3aed',
                'sort_order' => 1,
            ],
            [
                'slug' => 'ensayo',
                'name' => ['es' => 'Ensayos', 'en' => 'Rehearsals'],
                'color' => '#2563eb',
                'sort_order' => 2,
            ],
            [
                'slug' => 'salida',
                'name' => ['es' => 'Salidas', 'en' => 'Processions'],
                'color' => '#d97706',
                'sort_order' => 3,
            ],
            [
                'slug' => 'otros',
                'name' => ['es' => 'Otros', 'en' => 'Other'],
                'color' => '#64748b',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $cat) {
            EventCategory::query()->updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'color' => $cat['color'],
                    'sort_order' => $cat['sort_order'],
                    'is_active' => true,
                ],
            );
        }
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
