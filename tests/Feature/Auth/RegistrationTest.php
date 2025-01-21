<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('registers new user', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];
    $this->postJson('/api/register', $userData)
    ->assertStatus(200)
             ->assertJsonStructure([
                 'token',
                 'user' => ['name','email'],
             ]);
    $this->assertDatabaseHas('users', [
        'email' => $userData['email'],
    ]);
    $user = User::where('email', $userData['email'])->first();
    expect(Hash::check('password', $user->password))->toBeTrue();
});
it('returns errors for invalid input')->postJson('/api/register', [
        'name' => 'Jon',
        'email'=>'',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertStatus(422)->assertJsonValidationErrors(['email']);

it('returns validation errors for unmatched passwords')
    ->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 12345678,
        'password_confirmation' => 1234587,
    ])->assertStatus(422)->assertJsonValidationErrors(['password']);