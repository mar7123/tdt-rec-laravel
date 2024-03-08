<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "published_at" => fake()->dateTimeInInterval('-3 days', '+1 day'),
            "article_title" => fake()->text(90),
            "article_desc" => fake()->realText(1700, 2),
            "article_img" => fake()->imageUrl(640, 480, null, true),
        ];
    }
}
