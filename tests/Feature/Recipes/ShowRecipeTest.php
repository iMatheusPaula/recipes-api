<?php

use App\Models\Recipe;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can show a specific recipe with its relationships', function () {
    $recipe = Recipe::factory()->create();

    $reviews = Review::factory()
        ->count(3)
        ->create([
            'recipe_id' => $recipe->id,
            'rating' => 4
        ]);

    $response = $this->getJson("/api/recipes/{$recipe->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'description',
            'ingredients',
            'instructions',
            'user_id',
            'created_at',
            'updated_at',
            'average_rating',
            'user' => ['id', 'name', 'email'],
            'reviews' => ['*' => ['id', 'recipe_id', 'name', 'rating', 'comment']]
        ])
        ->assertJsonPath('id', $recipe->id)
        ->assertJsonPath('name', $recipe->name)
        ->assertJsonPath('description', $recipe->description)
        ->assertJsonPath('ingredients', $recipe->ingredients)
        ->assertJsonPath('instructions', $recipe->instructions)
        ->assertJsonPath('user_id', $recipe->user_id)
        ->assertJsonPath('average_rating', 4)
        ->assertJsonCount(3, 'reviews')
        ->assertJsonFragment([
            'id' => $recipe->user->id
        ])
        ->assertJsonFragment([
            'id' => $reviews[0]->id,
            'recipe_id' => $reviews[0]->recipe_id,
            'name' => $reviews[0]->name,
            'rating' => 4,
            'comment' => $reviews[0]->comment
        ]);
});

test('returns 404 when recipe does not exist', function () {
    $response = $this->getJson('/api/recipes/999');

    $response->assertStatus(404);
});

test('recipe shows correct average rating', function () {
    $recipe = Recipe::factory()->create();

    $review1 = Review::factory()->create([
        'recipe_id' => $recipe->id,
        'rating' => 5
    ]);

    $review2 = Review::factory()->create([
        'recipe_id' => $recipe->id,
        'rating' => 3
    ]);

    $review3 = Review::factory()->create([
        'recipe_id' => $recipe->id,
        'rating' => 1
    ]);

    $avg = ($review1['rating'] + $review2['rating'] + $review3['rating']) / 3;

    $response = $this->getJson("/api/recipes/{$recipe->id}");

    $response->assertStatus(200)
        ->assertJsonPath('average_rating', $avg);
});
