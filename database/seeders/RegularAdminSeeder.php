<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegularAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First make sure the role exists
        $adminRole = DB::table('role_names')->where('name', 'Admin')->first();
        
        if (!$adminRole) {
            // Create the role if it doesn't exist
            $adminRoleId = DB::table('role_names')->insertGetId([
                'name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $this->command->info('Created "Admin" role');
        } else {
            $adminRoleId = $adminRole->id;
        }
        
        // Create the regular admin with the Admin role
        $admin = User::updateOrCreate(
            [
                'username' => 'admin',
                'email' => 'admin@example.com'
            ],
            [
                'first_name' => 'Regular',
                'last_name' => 'Admin',
                'password' => Hash::make('admin123'), // Use a stronger password in production
                'role' => 'admin',
                'role_name' => 'Admin',
                'role_name_id' => $adminRoleId,
                'email_verified_at' => now() // Mark as verified
            ]
        );
        
        $this->command->info('Regular admin created/updated with "Admin" role');
    }
}
