<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreatePlatformsAndStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blazer:setup-platforms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create sample yearbook platforms and their stock records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Step 1: Create yearbook platforms
        $platforms = [
            [
                'year' => 2023,
                'name' => 'Blazer 2023 Yearbook',
                'theme_title' => 'Celebrating Excellence',
                'status' => 'active',
                'is_active' => 1, // MySQL boolean as 1/0
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2024,
                'name' => 'Blazer 2024 Yearbook',
                'theme_title' => 'New Horizons',
                'status' => 'active',
                'is_active' => 1, // MySQL boolean as 1/0
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert platforms and store their IDs
        $platformIds = [];
        foreach ($platforms as $platform) {
            try {
                // Use direct DB insert to avoid model issues
                $id = DB::table('yearbook_platforms')->insertGetId($platform);
                $platformIds[] = $id;
                $this->info("Created platform: " . $platform['name'] . " with ID: " . $id);
            } catch (\Exception $e) {
                $this->error("Error creating platform " . $platform['name'] . ": " . $e->getMessage());
            }
        }

        // Step 2: Create stock records for each platform
        foreach ($platformIds as $platformId) {
            try {
                $stock = [
                    'yearbook_platform_id' => $platformId,
                    'initial_stock' => 100,
                    'available_stock' => 100,
                    'price' => 2300.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Use direct DB insert to avoid model issues
                $stockId = DB::table('yearbook_stocks')->insertGetId($stock);
                $this->info("Created stock record for platform ID: " . $platformId . " with stock ID: " . $stockId);
            } catch (\Exception $e) {
                $this->error("Error creating stock for platform " . $platformId . ": " . $e->getMessage());
            }
        }

        $this->info("Platforms and stocks setup complete!");
    }
}
