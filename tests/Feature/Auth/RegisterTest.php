<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can register a new user with valid data', function () {
    $payload = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'P@ssword123',
        'password_confirmation' => 'P@ssword123',
    ];

    $response = $this->postJson('/api/register', $payload);

    $response->assertStatus(201)
        ->assertJsonStructure(['id', 'name', 'email', 'created_at', 'updated_at']);

    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

test('cannot register without required fields', function () {
    $response = $this->postJson('/api/register', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

test('cannot register with invalid email', function () {
    $payload = [
        'name' => 'Test User',
        'email' => 'testeexample.com',
        'password' => 'P@ssword123',
        'password_confirmation' => 'P@ssword123',
    ];

    $response = $this->postJson('/api/register', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('cannot register with password confirmation mismatch', function () {
    $payload = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'P@ssword123',
        'password_confirmation' => 'p@ssword123',
    ];

    $response = $this->postJson('/api/register', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('cannot register with password too short', function () {
    $payload = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'pass1',
        'password_confirmation' => 'pass1',
    ];

    $response = $this->postJson('/api/register', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('cannot register with email already in use', function () {
    User::factory()->create([
        'email' => 'existing@example.com',
    ]);

    $payload = [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'P@ssword123',
        'password_confirmation' => 'P@ssword123',
    ];

    $response = $this->postJson('/api/register', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
