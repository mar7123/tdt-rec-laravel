<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleComments>
 */
class ArticleCommentsFactory extends Factory
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
            "comment_desc" => fake()->realText(200, 2),
        ];
    }
}
