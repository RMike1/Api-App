<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('logs out the authenticated user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('TestToken')->plainTextToken;
    $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/logout')
    ->assertStatus(200)
    ->assertJson(['message' => 'Successfully logged out...']);
    $this->assertCount(0, $user->tokens);
});
