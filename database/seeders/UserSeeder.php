<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => 'password123',
        ]);

        // Assign admin role
        $adminRole = Role::where('guard_name', 'admin')->first();
        $admin->roles()->attach($adminRole->id);
    }
}
