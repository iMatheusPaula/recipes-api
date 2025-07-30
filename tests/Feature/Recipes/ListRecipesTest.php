<?php

use App\Models\Recipe;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can list all recipes', function () {
    $recipes = Recipe::factory()
        ->count(3)
        ->create();

    foreach ($recipes as $recipe) {
        Review::factory()
            ->count(2)
            ->create(['recipe_id' => $recipe->id]);
    }

    $response = $this->getJson('/api/recipes');

    $response->assertStatus(200)
        ->assertJsonCount(3)
        ->assertJsonStructure([
            '*' => [
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
            ]
        ]);

    $responseData = $response->json();

    foreach ($responseData as $recipeData) {
        expect($recipeData)
            ->toHaveKey('user')
            ->and($recipeData)->toHaveKey('reviews')
            ->and($recipeData['reviews'])->toHaveCount(2);
    }
});

test('returns empty when no recipes exist', function () {
    $response = $this->getJson('/api/recipes');

    $response->assertStatus(200);

    $response->assertJsonCount(0)
        ->assertJson([]);
});
