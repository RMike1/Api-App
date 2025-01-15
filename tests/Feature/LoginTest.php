<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);

beforeEach( function(){
    $this->user=User::factory()->create([
        'name' => 'johndoe',
        'email' => 'johndoe@gmail.com',
        'password' => bcrypt(12345678)
    ]);
});

it('authenticates the user with valid credentials', fn () =>
    $this->postJson('/api/login', [
        'email' => $this->user->email,
        'password' => 12345678,
    ])
    ->assertStatus(200)
    ->assertJsonStructure([
        'user' => ['name', 'email'],
        'token'
    ])
);

it('rejects login with invalid credentials', fn () =>
    $this->postJson('/api/login', [
        'email' => $this->user->email,
        'password' => 123456789,
    ])->assertStatus(401)
    ->assertJson(['message' => 'Unauthorized'])
);