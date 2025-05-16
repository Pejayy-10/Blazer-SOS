<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoleName;
use Illuminate\Support\Facades\DB;

class RoleNamesSeeder extends Seeder
{
    /**
     * Run the database seeds to create role names.
     */
    public function run(): void
    {
        // Define role names
        $roleNames = [
            'Editor in Chief',
            'Associate Editor',
            'Layout Editor',
            'Photojournalist',
            'Content Writer',
            'Graphic Designer',
            'Administrator',
            'Staff Member'
        ];

        // Create each role name
        foreach ($roleNames as $roleName) {
            DB::table('role_names')->updateOrInsert(
                ['name' => $roleName],
                ['created_at' => now(), 'updated_at' => now()]
            );

            $this->command->info("Created or updated role name: {$roleName}");
        }
    }
}
