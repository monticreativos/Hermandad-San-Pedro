<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique();
            $table->json('name');
            $table->string('color', 32);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();
        $defaults = [
            ['slug' => 'cultos', 'name' => ['es' => 'Cultos', 'en' => 'Worship'], 'color' => '#0284c7', 'sort_order' => 10],
            ['slug' => 'ensayo', 'name' => ['es' => 'Ensayo', 'en' => 'Rehearsal'], 'color' => '#d97706', 'sort_order' => 20],
            ['slug' => 'salida', 'name' => ['es' => 'Salida', 'en' => 'Procession'], 'color' => '#e11d48', 'sort_order' => 30],
            ['slug' => 'otros', 'name' => ['es' => 'Otros', 'en' => 'Other'], 'color' => '#a1a1aa', 'sort_order' => 40],
        ];

        foreach ($defaults as $row) {
            DB::table('event_categories')->insert([
                'slug' => $row['slug'],
                'name' => json_encode($row['name']),
                'color' => $row['color'],
                'sort_order' => $row['sort_order'],
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('event_category_id')
                ->nullable()
                ->after('date_time')
                ->constrained('event_categories')
                ->restrictOnDelete();
        });

        $idBySlug = DB::table('event_categories')->pluck('id', 'slug')->all();

        foreach (DB::table('events')->select('id', 'type')->cursor() as $ev) {
            $slug = $ev->type;
            $categoryId = $idBySlug[$slug] ?? $idBySlug['otros'];
            DB::table('events')->where('id', $ev->id)->update(['event_category_id' => $categoryId]);
        }

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('type', 32)->default('otros');
        });

        $slugById = DB::table('event_categories')->pluck('slug', 'id')->all();

        foreach (DB::table('events')->select('id', 'event_category_id')->cursor() as $ev) {
            $slug = $slugById[$ev->event_category_id] ?? 'otros';
            DB::table('events')->where('id', $ev->id)->update(['type' => $slug]);
        }

        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['event_category_id']);
            $table->dropColumn('event_category_id');
        });

        Schema::dropIfExists('event_categories');
    }
};
