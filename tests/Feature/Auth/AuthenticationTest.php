<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;

// Test: login screen can be rendered
test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

// Test: users can authenticate using the login screen
test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

// Tes: pengguna tidak dapat mengotentikasi dengan kata sandi yang tidak valid
test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

// Test: users can logout
test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
