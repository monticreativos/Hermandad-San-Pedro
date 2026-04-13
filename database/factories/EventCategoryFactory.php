<?php

namespace Database\Factories;

use App\Models\EventCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EventCategory>
 */
class EventCategoryFactory extends Factory
{
    protected $model = EventCategory::class;

    public function definition(): array
    {
        $es = fake()->unique()->words(2, true);

        return [
            'slug' => Str::slug($es.'-'.fake()->unique()->numerify('##')),
            'name' => [
                'es' => $es,
                'en' => fake()->words(2, true),
            ],
            'color' => '#'.str_pad(dechex(random_int(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT),
            'sort_order' => fake()->numberBetween(1, 99),
            'is_active' => true,
        ];
    }
}
