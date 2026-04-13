<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DynamicPagesSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CultosPagesSeeder::class,
            PatrimonioPagesSeeder::class,
            ObraSocialPagesSeeder::class,
        ]);
    }
}
