<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         $this->call([
             RoleNamesSeeder::class, // Seed role names first
             SuperAdminSeeder::class, // Create superadmin with Editor in Chief role
             RegularAdminSeeder::class, // Create regular admin
             CountryCitySeeder::class, // Seed countries and cities
             YearbookPlatformSeeder::class, // Create yearbook platforms
             YearbookStockSeeder::class, // Create yearbook stocks
             AcademicDataSeeder::class, // Seed colleges, courses, and majors
             // Add other seeders if needed
         ]);
    }
}