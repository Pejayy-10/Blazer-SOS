<?php

namespace App\Console\Commands;

use App\Models\YearbookPlatform;
use App\Models\YearbookStock;
use Illuminate\Console\Command;

class CreateYearbookStockRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-yearbook-stock-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create stock records for all yearbook platforms that do not have them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to create yearbook stock records...');
        
        // Get all platforms
        $platforms = YearbookPlatform::all();
        $this->info("Found {$platforms->count()} yearbook platforms.");
        
        $created = 0;
        $existing = 0;
        
        foreach ($platforms as $platform) {
            // Create stock record if it doesn't exist
            if (!$platform->stock()->exists()) {
                YearbookStock::create([
                    'yearbook_platform_id' => $platform->id,
                    'initial_stock' => 100, // Default initial stock
                    'available_stock' => 100, // Default available stock
                    'price' => 2300.00, // Default price (₱2,300)
                ]);
                
                $this->info("Created stock record for platform: {$platform->name} ({$platform->year})");
                $created++;
            } else {
                $this->info("Stock record already exists for platform: {$platform->name}");
                $existing++;
            }
        }
        
        $this->info("Completed! Created {$created} new stock records. {$existing} records already existed.");
    }
}
