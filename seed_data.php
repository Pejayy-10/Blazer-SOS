<?php

// Bootstrap Laravel
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\YearbookPlatform;
use App\Models\YearbookStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Make sure the tables exist
if (!Schema::hasTable('yearbook_platforms') || !Schema::hasTable('yearbook_stocks')) {
    echo "Error: Required tables don't exist.\n";
    exit(1);
}

// Create sample yearbook platforms
$platforms = [
    [
        'year' => 2023,
        'name' => 'Blazer 2023 Yearbook',
        'status' => 'archived',
        'is_active' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'year' => 2024,
        'name' => 'Blazer 2024 Yearbook',
        'status' => 'closed',
        'is_active' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'year' => 2025,
        'name' => 'Blazer 2025 Yearbook',
        'status' => 'open',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

// Insert platform records and create stock records for each
$count = 0;
foreach ($platforms as $platform) {
    try {
        // Insert the platform
        $platformId = DB::table('yearbook_platforms')->insertGetId($platform);
        
        // Create a stock record for this platform
        DB::table('yearbook_stocks')->insert([
            'yearbook_platform_id' => $platformId,
            'initial_stock' => 100,
            'available_stock' => 100,
            'price' => 2300.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "Created platform '{$platform['name']}' (ID: {$platformId}) with stock records.\n";
        $count++;
    } catch (Exception $e) {
        echo "Error creating platform '{$platform['name']}': " . $e->getMessage() . "\n";
    }
}

echo "Completed: Created {$count} platforms with stock records.\n";
