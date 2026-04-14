<?php

namespace Database\Seeders;

use App\Models\BrotherhoodPage;
use Illuminate\Database\Seeder;

class BrotherhoodPagesSeeder extends Seeder
{
    public function run(): void
    {
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
            array_merge(
                ['key' => 'aviso-legal'],
                require __DIR__.'/content/brotherhood_aviso_legal.php'
            ),
            array_merge(
                ['key' => 'politica-privacidad'],
                require __DIR__.'/content/brotherhood_politica_privacidad.php'
            ),
            array_merge(
                ['key' => 'politica-cookies'],
                require __DIR__.'/content/brotherhood_politica_cookies.php'
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
    }
}
