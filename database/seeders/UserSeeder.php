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
                'password' => bcrypt('superadmin@123'), // Use bcrypt for password hashing
                'role' => 'Admin', // Corrected role to match the table's enum values
                'status' => 'Y',
            ]
        );

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Unique constraint
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin@123'), // Use bcrypt for password hashing
                'role' => 'Admin',
                'status' => 'Y',
            ]
        );

        // Create Farmer
        User::updateOrCreate(
            ['email' => 'farmer@gmail.com'], // Unique constraint
            [
                'name' => 'Farmer User', // Corrected name to match the role
                'email' => 'farmer@gmail.com',
                'password' => bcrypt('farmer@123'), // Use bcrypt for password hashing
                'role' => 'Farmer',
                'status' => 'Y',
            ]
        );
    }
}
