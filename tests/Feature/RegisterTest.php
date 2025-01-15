<?php
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registers a new user')
->defer(fn(): mixed=>
    $this->postJson('/api/register', [
       'name' => 'Jon',
       'email' => 'jon@gmail.com',
       'password' => 12345678,
       'password_confirmation' => 12345678,
   ])
   ->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
            ])
)->assertDatabaseHas('users', [
        'email' => 'jon@gmail.com',
    ]);

