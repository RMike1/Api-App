<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach( function(){
    $this->user=User::factory()->create();
});

it('sends password reset link to user', fn () =>
    $this->actingAs($this->user, 'sanctum')
    ->postJson('/api/forgot-password', [
        'email' => $this->user->email,
    ])->assertOk()
    ->assertJson(['status' => __('passwords.sent')])
);

it('fails to send reset link when the email field is empty', fn() =>
    $this->actingAs($this->user, 'sanctum')
    ->postJson(route('forgot.password'), ['email' => ''])
    ->assertStatus(422)
    ->assertJsonValidationErrors(['email'])
);