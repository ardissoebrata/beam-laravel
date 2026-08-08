<?php

test('forgot password screen cannot be rendered when reset passwords is disabled', function () {
    $response = $this->get('/forgot-password');

    $response->assertNotFound();
});

test('reset password link cannot be requested when reset passwords is disabled', function () {
    $response = $this->post('/forgot-password', [
        'email' => 'test@example.com',
    ]);

    $response->assertNotFound();
});

test('reset password screen cannot be rendered when reset passwords is disabled', function () {
    $response = $this->get('/reset-password/fake-token');

    $response->assertNotFound();
});

test('password cannot be reset when reset passwords is disabled', function () {
    $response = $this->post('/reset-password/fake-token', [
        'token' => 'fake-token',
        'email' => 'test@example.com',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertNotFound();
});

test('login page does not show forgot password link when reset passwords is disabled', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertDontSeeText('Forgot your password?');
});
