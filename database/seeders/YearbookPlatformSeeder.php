<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\YearbookPlatform;
use Illuminate\Support\Facades\DB;

class YearbookPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample yearbook platforms
        $platforms = [
            [
                'year' => 2023,
                'name' => 'Blazer 2023 Yearbook',
                'theme_title' => 'Celebrating Excellence',
                'status' => 'active',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2024,
                'name' => 'Blazer 2024 Yearbook',
                'theme_title' => 'New Horizons',
                'status' => 'active',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($platforms as $platform) {
            DB::table('yearbook_platforms')->insert($platform);
            $this->command->info("Created yearbook platform: {$platform['name']} ({$platform['year']})");
        }
    }
}
