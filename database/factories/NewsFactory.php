<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titleEs = $this->faker->sentence(4);
        $titleEn = $this->faker->sentence(4);

        return [
            'title' => [
                'es' => $titleEs,
                'en' => $titleEn,
            ],
            'content' => [
                'es' => $this->faker->paragraphs(3, true),
                'en' => $this->faker->paragraphs(3, true),
            ],
            'image_path' => null,
            'is_published' => $this->faker->boolean(70),
        ];
    }
}
