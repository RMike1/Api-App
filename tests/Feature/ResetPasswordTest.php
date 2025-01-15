<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});


it('does not reset the password with an invalid token',fn() =>
    $this->postJson(route('reset.password'), [
        'email' => $this->user->email,
        'password' => 12345678,
        'password_confirmation' => 12345678,
        'token' => 'invalid-tok...',
    ])->assertStatus(500)
);

it('does not reset the password when the email is missing',closure: fn() =>
    $this->postJson(route('reset.password'), [
        'token' => Password::createToken($this->user),
        'password' => 12345678,
        'password_confirmation' => 12345678,
    ])->assertStatus(422)
        ->assertJsonValidationErrors(['email'])
);

it( 'does not reset the password when the passwords do not match',fn() =>
    $this->postJson(route('reset.password'), [
        'email' => $this->user->email,
        'password' => 12345678,
        'password_confirmation' => 12345687,
        'token' => Password::createToken($this->user),
    ])->assertStatus(422)
        ->assertJsonValidationErrors(['password'])
);

it('resets the password successfully with a valid token')
    ->defer(
        fn() =>
        $this->postJson(route('reset.password'), [
            'email' => $this->user->email,
            'password' => 12345678,
            'password_confirmation' => 12345678,
            'token' => Password::createToken($this->user),
        ])
            ->assertStatus(200)
            ->assertJson(['message' => 'Reset password successfully'])
    );
