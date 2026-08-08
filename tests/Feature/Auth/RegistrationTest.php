<?php

test('registration screen cannot be rendered when registration is disabled', function () {
    $response = $this->get('/register');

    $response->assertNotFound();
});

test('new users cannot register when registration is disabled', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertNotFound();
    $this->assertGuest();
});

test('login and welcome pages do not show registration links when registration is disabled', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertDontSeeText('Sign up')
        ->assertDontSeeText('Don\'t have an account?');

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSeeText('Register');
});
