<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoleName;
use Illuminate\Support\Facades\DB;

class RoleNameSeeder extends Seeder
{
    /**
     * Seed the role_names table with standard staff roles
     */
    public function run(): void
    {
        $roles = [
            'Editor in Chief',
            'Associate Editor',
            'Managing Editor',
            'Copy Editor',
            'Layout Artist',
            'Photojournalist',
            'Writer',
            'Staff',
            'Adviser',
        ];

        foreach ($roles as $role) {
            // Use DB statement in case model doesn't support some attributes
            DB::table('role_names')->insertOrIgnore([
                'name' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info("Added role: {$role}");
        }
    }
}
