<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'recipe_id' => Recipe::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->text(),
            'ip_address' => fake()->ipv4(),
        ];
    }
}
