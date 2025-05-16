<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Import User model
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Illuminate\Support\Facades\DB; // Import DB facade

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First make sure the role exists
        $editorInChiefRole = DB::table('role_names')->where('name', 'Editor in Chief')->first();
        
        if (!$editorInChiefRole) {
            // Create the role if it doesn't exist
            $editorInChiefId = DB::table('role_names')->insertGetId([
                'name' => 'Editor in Chief',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $this->command->info('Created "Editor in Chief" role');
        } else {
            $editorInChiefId = $editorInChiefRole->id;
        }
        
        // Create the superadmin with the Editor in Chief role
        $superadmin = User::updateOrCreate(
            [
                'username' => 'superadmin', // Or choose a specific username
                'email' => 'superadmin@example.com' // Use a real email if needed
            ],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password123'), // Use a STRONG password in production!
                'role' => 'superadmin', // System role
                'role_name' => 'Editor in Chief', // String representation of role
                'role_name_id' => $editorInChiefId, // Assign the Editor in Chief role
                'email_verified_at' => now() // Mark as verified
            ]
        );
        
        $this->command->info('Superadmin created/updated with "Editor in Chief" role');
    }
}