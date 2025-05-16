<?php
// Create yearbook platforms and stock records

use App\Models\YearbookPlatform;
use App\Models\YearbookStock;
use Illuminate\Support\Facades\DB;

// Step 1: Create yearbook platforms
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

// Insert platforms and store their IDs
$platformIds = [];
foreach ($platforms as $platform) {
    try {
        // Use direct DB insert to avoid model issues
        $id = DB::table('yearbook_platforms')->insertGetId($platform);
        $platformIds[] = $id;
        echo "Created platform: " . $platform['name'] . " with ID: " . $id . "\n";
    } catch (Exception $e) {
        echo "Error creating platform " . $platform['name'] . ": " . $e->getMessage() . "\n";
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
        echo "Created stock record for platform ID: " . $platformId . " with stock ID: " . $stockId . "\n";
    } catch (Exception $e) {
        echo "Error creating stock for platform " . $platformId . ": " . $e->getMessage() . "\n";
    }
}

echo "Platforms and stocks setup complete!\n";
