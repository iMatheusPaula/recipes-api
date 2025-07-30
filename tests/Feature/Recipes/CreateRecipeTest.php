<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

test('authenticated user can create a recipe and the user_id comes automatically', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $payload = [
        'name' => 'Teste receita',
        'description' => 'Descrição da receita',
        'ingredients' => 'Ingredientes',
        'instructions' => 'Instruções'
    ];

    $response = $this->postJson('/api/recipes', $payload);

    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            ...$payload,
            'user_id' => $user->id
        ]);
});

test('unauthenticated user cannot create a recipe', function () {
    $payload = [
        'name' => 'Teste receita',
        'description' => 'Descrição da receita',
        'ingredients' => 'Ingredientes',
        'instructions' => 'Instruções'
    ];

    $response = $this->postJson('/api/recipes', $payload);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
});
