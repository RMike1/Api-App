<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('logs out an authenticated user', fn  () =>        
    $this->actingAs(User::factory()->create(), 'sanctum')
        ->postJson('/api/logout')
        ->assertStatus(200)
        ->assertExactJson([
                 'message' => 'Successfully logged out...',
             ])
);
