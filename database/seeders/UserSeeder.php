<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'], // Unique constraint (prevents duplicate seeding)
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => md5('superadmin@123'), // Change password as needed
                'role' => 'Super_Admin',
                'status' => 'Y',
            ]
        );

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Unique constraint
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => md5('admin@123'), // Change password as needed
                'role' => 'Admin',
                'status' => 'Y',
            ]
        );
    }
}
