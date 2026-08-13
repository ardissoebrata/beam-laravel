<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('Admin', 'web');
    Role::findOrCreate('User', 'web');
    $permission = Permission::findOrCreate('users.manage', 'web');
    Role::findByName('Admin', 'web')->givePermissionTo($permission);
});

function adminUser(): User
{
    $user = User::factory()->create();
    $user->assignRole('Admin');

    return $user;
}

function regularUser(): User
{
    $user = User::factory()->create();
    $user->assignRole('User');

    return $user;
}

test('users page is displayed', function () {
    $user = adminUser();

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Users')
            ->has('users.data', 1)
            ->where('users.data.0.role', 'Admin')
            ->where('roles', ['Admin', 'User'])
            ->where('users.current_page', 1)
            ->where('users.per_page', 10)
            ->where('users.total', 1));
});

test('users can be searched, sorted, and paginated', function () {
    $user = adminUser()->forceFill(['name' => 'Middle User', 'email' => 'middle@example.com']);
    $user->save();
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
    $user = adminUser();

    $this->actingAs($user)
        ->get(route('users.index', ['sort_field' => 'password']))
        ->assertSessionHasErrors('sort_field');
});

test('user creation can be validated with precognition without creating a user', function () {
    $user = adminUser();

    $response = $this->actingAs($user)
        ->withPrecognition()
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'User',
        ]);

    $response->assertSuccessfulPrecognition();
    expect(User::count())->toBe(1);
});

test('user creation precognition returns validation errors', function () {
    $user = adminUser();

    $response = $this->actingAs($user)
        ->withPrecognition()
        ->post(route('users.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'different',
            'role' => 'User',
        ]);

    $response->assertSessionHasErrors([
        'name',
        'email',
        'password',
    ]);
    expect(User::count())->toBe(1);
});

test('user update can be validated with precognition without updating a user', function () {
    $user = adminUser();
    $managedUser = User::factory()->create(['name' => 'Original User']);

    $response = $this->actingAs($user)
        ->withPrecognition()
        ->patch(route('users.update', $managedUser), [
            'name' => 'Updated User',
            'email' => $managedUser->email,
            'role' => 'User',
        ]);

    $response->assertSuccessfulPrecognition();
    expect($managedUser->refresh()->name)->toBe('Original User');
});

test('user update precognition returns validation errors', function () {
    $user = adminUser();
    $managedUser = User::factory()->create(['name' => 'Original User']);

    $response = $this->actingAs($user)
        ->withPrecognition()
        ->patch(route('users.update', $managedUser), [
            'name' => '',
            'email' => 'not-an-email',
            'role' => 'User',
        ]);

    $response->assertSessionHasErrors(['name', 'email']);
    expect($managedUser->refresh()->name)->toBe('Original User');
});

test('user can be created', function () {
    $user = adminUser();

    $response = $this->actingAs($user)->post(route('users.store'), [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'User',
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect(route('users.index'));

    $createdUser = User::where('email', 'new@example.com')->firstOrFail();
    expect($createdUser->name)->toBe('New User');
    expect($createdUser->hasRole('User'))->toBeTrue();
    expect(Hash::check('password', $createdUser->password))->toBeTrue();
});

test('user can be updated without changing the password', function () {
    $user = adminUser();
    $managedUser = User::factory()->create(['password' => 'old-password']);
    $password = $managedUser->password;

    $response = $this->actingAs($user)->patch(route('users.update', $managedUser), [
        'name' => 'Updated User',
        'email' => $managedUser->email,
        'role' => 'User',
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect(route('users.index'));
    expect($managedUser->refresh()->name)->toBe('Updated User');
    expect($managedUser->password)->toBe($password);
});

test('user can be updated when the password fields are null', function () {
    $user = adminUser();
    $managedUser = User::factory()->create(['password' => 'old-password']);
    $password = $managedUser->password;

    $response = $this->actingAs($user)->patch(route('users.update', $managedUser), [
        'name' => 'Updated User',
        'email' => $managedUser->email,
        'role' => 'User',
        'password' => null,
        'password_confirmation' => null,
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect(route('users.index'));
    expect($managedUser->refresh()->name)->toBe('Updated User');
    expect($managedUser->password)->toBe($password);
});

test('user password can be updated', function () {
    $user = adminUser();
    $managedUser = User::factory()->create();

    $response = $this->actingAs($user)->patch(route('users.update', $managedUser), [
        'name' => $managedUser->name,
        'email' => $managedUser->email,
        'role' => 'User',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
        'role' => 'User',
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect(route('users.index'));
    expect(Hash::check('new-password', $managedUser->refresh()->password))->toBeTrue();
});

test('user can be deleted but their own account cannot be deleted', function () {
    $user = adminUser();
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

test('regular users cannot manage users', function () {
    $user = regularUser();
    $managedUser = User::factory()->create();

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertForbidden();

    $this->actingAs($user)
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'User',
        ])
        ->assertForbidden();

    $this->actingAs($user)
        ->patch(route('users.update', $managedUser), [
            'name' => 'Updated User',
            'email' => $managedUser->email,
        ])
        ->assertForbidden();

    $this->actingAs($user)
        ->delete(route('users.destroy', $managedUser))
        ->assertForbidden();
});

test('database seeder assigns the requested roles', function () {
    $this->seed();

    expect(User::where('email', 'admin@example.com')->firstOrFail()->hasRole('Admin'))->toBeTrue();
    expect(User::where('email', 'test@example.com')->firstOrFail()->hasRole('User'))->toBeTrue();
    expect(Role::whereIn('name', ['Admin', 'User'])->count())->toBe(2);
    expect(Permission::where('name', 'users.manage')->exists())->toBeTrue();
});
