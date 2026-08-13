<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('users page is displayed', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Users')
            ->has('users.data', 1)
            ->where('users.current_page', 1)
            ->where('users.per_page', 10)
            ->where('users.total', 1));
});

test('users can be searched, sorted, and paginated', function () {
    $user = User::factory()->create(['name' => 'Middle User', 'email' => 'middle@example.com']);
    User::factory()->create(['name' => 'Zoe User', 'email' => 'zoe@example.com']);
    User::factory()->create(['name' => 'Alice User', 'email' => 'alice@example.com']);

    $this->actingAs($user)
        ->get(route('users.index', [
            'search' => 'User',
            'sort_field' => 'name',
            'sort_order' => 'asc',
            'per_page' => 2,
        ]))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->where('users.current_page', 1)
            ->where('users.per_page', 2)
            ->where('users.total', 3)
            ->where('users.data.0.name', 'Alice User')
            ->where('filters.search', 'User'));
});

test('users table query parameters are validated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('users.index', ['sort_field' => 'password']))
        ->assertSessionHasErrors('sort_field');
});

test('user creation can be validated with precognition without creating a user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->withPrecognition()
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

    $response->assertSuccessfulPrecognition();
    expect(User::count())->toBe(1);
});

test('user creation precognition returns validation errors', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->withPrecognition()
        ->post(route('users.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

    $response->assertSessionHasErrors([
        'name',
        'email',
        'password',
    ]);
    expect(User::count())->toBe(1);
});

test('user update can be validated with precognition without updating a user', function () {
    $user = User::factory()->create();
    $managedUser = User::factory()->create(['name' => 'Original User']);

    $response = $this->actingAs($user)
        ->withPrecognition()
        ->patch(route('users.update', $managedUser), [
            'name' => 'Updated User',
            'email' => $managedUser->email,
        ]);

    $response->assertSuccessfulPrecognition();
    expect($managedUser->refresh()->name)->toBe('Original User');
});

test('user update precognition returns validation errors', function () {
    $user = User::factory()->create();
    $managedUser = User::factory()->create(['name' => 'Original User']);

    $response = $this->actingAs($user)
        ->withPrecognition()
        ->patch(route('users.update', $managedUser), [
            'name' => '',
            'email' => 'not-an-email',
        ]);

    $response->assertSessionHasErrors(['name', 'email']);
    expect($managedUser->refresh()->name)->toBe('Original User');
});

test('user can be created', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('users.store'), [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect(route('users.index'));

    $createdUser = User::where('email', 'new@example.com')->firstOrFail();
    expect($createdUser->name)->toBe('New User');
    expect(Hash::check('password', $createdUser->password))->toBeTrue();
});

test('user can be updated without changing the password', function () {
    $user = User::factory()->create();
    $managedUser = User::factory()->create(['password' => 'old-password']);
    $password = $managedUser->password;

    $response = $this->actingAs($user)->patch(route('users.update', $managedUser), [
        'name' => 'Updated User',
        'email' => $managedUser->email,
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect(route('users.index'));
    expect($managedUser->refresh()->name)->toBe('Updated User');
    expect($managedUser->password)->toBe($password);
});

test('user can be updated when the password fields are null', function () {
    $user = User::factory()->create();
    $managedUser = User::factory()->create(['password' => 'old-password']);
    $password = $managedUser->password;

    $response = $this->actingAs($user)->patch(route('users.update', $managedUser), [
        'name' => 'Updated User',
        'email' => $managedUser->email,
        'password' => null,
        'password_confirmation' => null,
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect(route('users.index'));
    expect($managedUser->refresh()->name)->toBe('Updated User');
    expect($managedUser->password)->toBe($password);
});

test('user password can be updated', function () {
    $user = User::factory()->create();
    $managedUser = User::factory()->create();

    $response = $this->actingAs($user)->patch(route('users.update', $managedUser), [
        'name' => $managedUser->name,
        'email' => $managedUser->email,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect(route('users.index'));
    expect(Hash::check('new-password', $managedUser->refresh()->password))->toBeTrue();
});

test('user can be deleted but their own account cannot be deleted', function () {
    $user = User::factory()->create();
    $managedUser = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('users.destroy', $managedUser))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('users.index'));

    expect($managedUser->fresh())->toBeNull();

    $this->actingAs($user)
        ->delete(route('users.destroy', $user))
        ->assertForbidden();

    expect($user->fresh())->not->toBeNull();
});

test('guests cannot manage users', function () {
    $this->get(route('users.index'))->assertRedirect(route('login'));
});
