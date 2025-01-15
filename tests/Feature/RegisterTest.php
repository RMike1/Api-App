<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->user = User::factory()->create();
});

it('registers new user')
    ->defer(
        fn(): mixed =>
        $this->postJson('/api/register', [
            'name' => 'Jon',
            'email' => 'jon@gmail.com',
            'password' => 12345678,
            'password_confirmation' => 12345678,
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
            ])
    )->assertDatabaseHas('users', [
        'email' => 'jon@gmail.com',
    ]);
it('fails to register new user when data are not provided')
    ->defer(
        fn(): mixed =>
        $this->postJson('/api/register', [
            'name' => 'Jon',
            'email' => '',
            'password' => 12345678,
            'password_confirmation' => 12345678,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
    )->assertDatabaseMissing('users', [
        'email' => 'jon@gmail.com',
    ]);
it('fails to register when passwords are not match')
    ->defer(
        fn(): mixed =>
        $this->postJson('/api/register', [
            'name' => 'Jon',
            'email' => 'jon@gmail.com',
            'password' => 1234567890,
            'password_confirmation' => 1234567887,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password'])
    )->assertDatabaseMissing('users', [
        'email' => 'jon@gmail.com',
    ]);

it('fails when registering with an existing email')->defer(
    fn() =>
    $this->postJson('api/register', [
        'name' => 'jon',
        'email' => $this->user->email,
        'password' => '1234567890',
        'password_confirmation' => '1234567890',
    ])->assertStatus(422)
        ->assertJsonValidationErrors(['email'])
)
->assertDatabaseCount('users',1)
->assertDatabaseMissing('users', ['email' => 'jon@gmail.com']);