<?php

namespace Database\Seeders;

use App\Models\ObraSocialPage;
use Illuminate\Database\Seeder;

class ObraSocialPagesSeeder extends Seeder
{
    public function run(): void
    {
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
    }
}
