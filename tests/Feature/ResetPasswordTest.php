<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('successfully resets the password with a valid token')
    ->defer(
        fn() =>
        $this->postJson(route('reset.password'), [
            'email' => $this->user->email,
            'password' => 12345,
            'password_confirmation' => 12345,
            'token' => Password::createToken($this->user),
        ])
            ->assertStatus(200)
            ->assertJson(['message' => 'Password reset successfully'])
    );

it(
    'fails to reset the password when token is not valid',
    fn() =>
    $this->postJson(route('reset.password'), [
        'email' => $this->user->email,
        'password' => 12345,
        'password_confirmation' => 12345,
        'token' => 'invalid-token',
    ])->assertStatus(500)
);

it(
    'fails to reset the password if the email is not provided',
    closure: fn() =>
    $this->postJson(route('reset.password'), [
        'token' => Password::createToken($this->user),
        'password' => 12345,
        'password_confirmation' => 12345,
    ])->assertStatus(422)
        ->assertJsonValidationErrors(['email'])
);

it(
    'fails to reset the password if the passwords do not match',
    fn() =>
    $this->postJson(route('reset.password'), [
        'email' => $this->user->email,
        'password' => 12345,
        'password_confirmation' => 123456,
        'token' => Password::createToken($this->user),
    ])->assertStatus(422)
        ->assertJsonValidationErrors(['password'])
);
