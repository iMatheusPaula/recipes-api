<?php

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

test('owner can delete their recipe', function () {
    $user = User::factory()->create();

    $recipe = Recipe::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $response = $this->deleteJson("/api/recipes/{$recipe->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);

    $this->assertDatabaseMissing('recipes', [
        'id' => $recipe->id
    ]);
});

test('non-owner cannot delete recipe', function () {
    $owner = User::factory()->create();

    $otherUser = User::factory()->create();

    $recipe = Recipe::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($otherUser);

    $response = $this->deleteJson("/api/recipes/{$recipe->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);

    $this->assertDatabaseHas('recipes', [
        'id' => $recipe->id
    ]);
});

test('unauthenticated user cannot delete recipe', function () {
    $recipe = Recipe::factory()->create();

    $response = $this->deleteJson("/api/recipes/{$recipe->id}");

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    $this->assertDatabaseHas('recipes', [
        'id' => $recipe->id
    ]);
});

test('returns 404 when trying to delete non-existent recipe', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->deleteJson('/api/recipes/999');

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});
