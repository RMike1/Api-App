<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(
    fn() =>
    $this->user = User::factory()->create()
);

it('logs out the authenticated user', function () {
    Sanctum::actingAs($this->user);
    $this->postJson('/api/logout')
        ->assertStatus(200)
        ->assertJson(['message' => 'Successfully logged out...']);
    $this->assertCount(0, $this->user->tokens);
    $this->assertGuest('api');
});

it('fails when the user is not authenticated')->postJson('/api/logout')
    ->assertStatus(401)
    ->assertJson(['message' => 'Unauthenticated.']);