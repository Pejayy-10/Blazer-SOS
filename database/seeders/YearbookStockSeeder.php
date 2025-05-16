<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\YearbookPlatform;
use App\Models\YearbookStock;
use Illuminate\Support\Facades\DB;

class YearbookStockSeeder extends Seeder
{
    /**
     * Run the database seeds to initialize stock records for existing platforms.
     */
    public function run(): void
    {
        // Get all platforms
        $platforms = YearbookPlatform::all();
        $this->command->info("Found {$platforms->count()} yearbook platforms.");
        
        $count = 0;
        
        foreach ($platforms as $platform) {
            // Try both methods to check if stock exists
            try {
                // Method 1: Check directly in the database
                $exists = DB::table('yearbook_stocks')
                    ->where('yearbook_platform_id', $platform->id)
                    ->exists();
                    
                if (!$exists) {
                    // Create stock record using direct DB insert
                    DB::table('yearbook_stocks')->insert([
                        'yearbook_platform_id' => $platform->id,
                        'initial_stock' => 100,  // Default initial stock
                        'available_stock' => 100, // Default available stock
                        'price' => 2300.00, // Default price (₱2,300)
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $count++;
                    $this->command->info("Created stock record for platform: {$platform->name} ({$platform->year})");
                } else {
                    $this->command->info("Stock record already exists for platform: {$platform->name}");
                }
            } catch (\Exception $e) {
                $this->command->error("Error checking/creating stock for platform {$platform->id}: " . $e->getMessage());
            }
        }
        
        $this->command->info("Created {$count} new stock records.");
    }
}
