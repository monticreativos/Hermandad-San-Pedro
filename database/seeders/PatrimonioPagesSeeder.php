<?php

namespace Database\Seeders;

use App\Models\PatrimonioItem;
use App\Models\PatrimonioItemCategory;
use App\Models\PatrimonioPage;
use Illuminate\Database\Seeder;

class PatrimonioPagesSeeder extends Seeder
{
    public function run(): void
    {
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
    }
}
