<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Modules\Roles\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve role IDs by name
        $roles = Role::pluck('id', 'name');

        // Define users and their respective roles
        $users = [
            [
                'name' => 'Usuario Admin',
                'email' => 'admin@godaddy.com',
                'password' => Hash::make('password123'),
                'roles' => ['admin'], // Single role
            ],
            [
                'name' => 'Usuario Contable',
                'email' => 'accountant@godaddy.com',
                'password' => Hash::make('password123'),
                'roles' => ['accountant'], // Single role
            ]
        ];

        // Insert users and assign roles
        foreach ($users as $userData) {
            // Map role names to role IDs
            $rolesToAttach = array_map(fn($role) => $roles[$role], $userData['roles']);
            unset($userData['roles']); // Remove 'roles' key before creating the user

            // Create user
            $user = User::create($userData);

            // Attach roles to the user
            $user->roles()->attach($rolesToAttach);
        }
    }
}
