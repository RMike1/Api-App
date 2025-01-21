<?php

use App\Models\User;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(fn() =>
    $this->user = User::factory()->create([
        'name'=>'Jon',
        'email'=>'jon@gmail.com',
        'password'=>Hash::make('12345678'),
    ])
);

test('users can login', function () {
    $this->postJson('/api/login', [
        'email' => $this->user->email,
        'password' => '12345678',
    ])->assertStatus(200)
    ->assertJsonStructure([
        'user' => ['name','email'],
        'token',
    ]);
    $this->assertAuthenticatedAs($this->user);
});

test('user can not authenticate with invalid password')->postJson('/api/login', [
        'email' => 'jon@gmail.com',
        'password' => '123456789',
    ])->assertStatus(422)->assertJson(['message' => 'These credentials do not match our records.']);

test('users can logout', function () {
    Sanctum::actingAs(User::factory()->create());
    $this->post('/api/logout')
    ->assertStatus(200)
        ->assertJson(['message' => 'Logged out successfully']);
        $this->assertGuest('api')
        ->assertDatabaseCount('personal_access_tokens',0);
});
