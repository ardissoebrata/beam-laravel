<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::findOrCreate('Admin', 'web');
        $userRole = Role::findOrCreate('User', 'web');
        $manageUsers = Permission::findOrCreate('users.manage', 'web');

        $adminRole->syncPermissions([$manageUsers]);
        $userRole->syncPermissions([]);

        User::factory(50)->create()->each->assignRole($userRole);

        User::updateOrCreate(['email' => 'test@example.com'], [
            'name' => 'Test User',
            'password' => 'password',
        ])->syncRoles([$userRole]);

        User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin User',
            'password' => 'password',
        ])->syncRoles([$adminRole]);
    }
}
