<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'es' => $this->faker->sentence(3),
                'en' => $this->faker->sentence(3),
            ],
            'description' => [
                'es' => $this->faker->paragraphs(2, true),
                'en' => $this->faker->paragraphs(2, true),
            ],
            'location' => $this->faker->address(),
            'date_time' => $this->faker->dateTimeBetween('now', '+6 months'),
            'event_category_id' => EventCategory::factory(),
            'gallery' => [],
        ];
    }
}
