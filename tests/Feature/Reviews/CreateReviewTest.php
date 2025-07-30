<?php

use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

test('can create a review for a recipe', function () {
    $recipe = Recipe::factory()->create();

    $payload = [
        'name' => 'Teste review',
        'rating' => 2,
        'comment' => 'Comentário sobre a receita'
    ];

    $response = $this->postJson("/api/recipes/{$recipe->id}/reviews", $payload);

    $response->assertStatus(Response::HTTP_CREATED);

    $this->assertDatabaseHas('reviews', [
        'recipe_id' => $recipe->id,
        ...$payload
    ]);
});

test('cannot create a review without rating', function () {
    $recipe = Recipe::factory()->create();

    $payload = [
        'name' => 'Teste review',
        'comment' => 'Comentário sobre a receita'
    ];

    $response = $this->postJson("/api/recipes/{$recipe->id}/reviews", $payload);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['rating']);
});

test('cannot create multiple reviews from same ip address', function () {
    $recipe = Recipe::factory()->create();

    $payload = [
        'rating' => 4,
        'comment' => 'Comentário sobre a receita'
    ];

    $response = $this->postJson("/api/recipes/{$recipe->id}/reviews", $payload);

    $response->assertStatus(Response::HTTP_CREATED);

    $payload = [
        'rating' => 1,
        'comment' => 'Tentando comentar de novo sobre a receita'
    ];

    $response = $this->postJson("/api/recipes/{$recipe->id}/reviews", $payload);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['review']);
});
