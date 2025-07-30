<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('user can login with valid credentials', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('P@ssword123'),
    ]);

    $payload = [
        'email' => 'test@example.com',
        'password' => 'P@ssword123',
    ];

    $response = $this->postJson('/api/login', $payload);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
        ]);
});

test('user cannot login with invalid password', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('P@ssword123'),
    ]);

    $payload = [
        'email' => 'test@example.com',
        'password' => 'laj@Awd123',
    ];

    $response = $this->postJson('/api/login', $payload);

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'As credenciais estão incorretas.',
            'errors' => [
                'email' => [
                    'As credenciais estão incorretas.'
                ]
            ]
        ]);
});

test('user cannot login with invalid email', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('P@ssword123'),
    ]);

    $payload = [
        'email' => 'test2@example.com',
        'password' => 'P@ssword123',
    ];

    $response = $this->postJson('/api/login', $payload);

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'As credenciais estão incorretas.',
            'errors' => [
                'email' => [
                    'As credenciais estão incorretas.'
                ]
            ]
        ]);
});

test('user cannot login without required fields', function () {
    $response = $this->postJson('/api/login', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

test('user cannot login with invalid email format', function () {
    $payload = [
        'email' => 'testexample.com',
        'password' => 'P@ssword123',
    ];

    $response = $this->postJson('/api/login', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
