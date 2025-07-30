<?php

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

test('owner can update their recipe', function () {
    $user = User::factory()->create();

    $recipe = Recipe::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $payload = [
        'name' => 'Teste receita',
        'description' => 'Descrição da receita',
        'ingredients' => 'Ingredientes',
        'instructions' => 'Instruções'
    ];

    $response = $this->putJson("/api/recipes/{$recipe->id}", $payload);

    $this->assertDatabaseHas('recipes', [
        ...$payload,
        'id' => $recipe->id,
        'user_id' => $user->id,
    ]);

    expect($response)
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            ...$payload,
            'id' => $recipe->id,
            'user_id' => $user->id,
        ]);
});

test('non-owner cannot update recipe', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $recipe = Recipe::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($otherUser);

    $payload = ['name' => 'Atualizando nome'];

    $response = $this->putJson("/api/recipes/{$recipe->id}", $payload);

    $response->assertStatus(Response::HTTP_FORBIDDEN);

    $this->assertDatabaseMissing('recipes', [
        'id' => $recipe->id,
        ...$payload
    ]);
});

test('unauthenticated user cannot update recipe', function () {
    $recipe = Recipe::factory()->create();

    $payload = ['name' => 'Atualizando nome'];

    $response = $this->putJson("/api/recipes/{$recipe->id}", $payload);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    $this->assertDatabaseMissing('recipes', [
        'id' => $recipe->id,
        ...$payload
    ]);
});
