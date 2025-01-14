<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach( function(){
    $this->user=User::factory()->create();
});


it('reset link to the user with a valid email', fn () =>
    $this->actingAs($this->user, 'sanctum')
    ->postJson('/api/forgot-password', [
        'email' => $this->user->email,
    ])->assertStatus(200)
    ->assertJson(['status' => __('passwords.sent')])
);

it('can not send reset link if email field is empty', fn() =>
    $this->actingAs($this->user, 'sanctum')
    ->postJson(route('forgot.password'), [
        'email' => '',
    ])->assertStatus(422)
    ->assertJsonValidationErrors(['email'])
);