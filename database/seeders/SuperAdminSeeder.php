<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Import User model
use Illuminate\Support\Facades\Hash; // Import Hash facade

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'username' => 'superadmin', // Or choose a specific username
                'email' => 'superadmin@example.com' // Use a real email if needed
            ],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password'), // Use a STRONG password here!
                'role' => 'superadmin', // Set the role
                'email_verified_at' => now() // Mark as verified if needed
            ]
        );
    }
}