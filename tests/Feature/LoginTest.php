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

it('login with correct details ', fn () =>
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

it('cannot login with wrong credentials', function () {
    $response = $this->postJson('/api/login', [
        'email' => $this->user->email,
        'password' => 123456789,
    ]);

    $response->assertStatus(401);
    $response->assertJson(['message' => 'Unauthorized']);
});


