<?php

// Fix script for creating yearbook_stocks table if it doesn't exist
// Run this with: php fix_yearbook_stocks.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\YearbookPlatform;
use App\Models\YearbookStock;

echo "Starting YearbookStocks fix script...\n";

// Check if table exists
if (!Schema::hasTable('yearbook_stocks')) {
    echo "Creating yearbook_stocks table...\n";
    
    Schema::create('yearbook_stocks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('yearbook_platform_id')->constrained()->onDelete('cascade');
        $table->integer('initial_stock')->default(0);
        $table->integer('available_stock')->default(0);
        $table->decimal('price', 10, 2)->nullable();
        $table->timestamps();
        
        // Ensure one stock record per platform
        $table->unique('yearbook_platform_id');
    });
    
    echo "Table 'yearbook_stocks' created successfully!\n";
} else {
    echo "Table 'yearbook_stocks' already exists.\n";
}

// Now create stock records for any platforms that don't have them
echo "Checking for platforms without stock records...\n";

$platforms = YearbookPlatform::all();
$count = 0;

foreach ($platforms as $platform) {
    // Check if a stock record already exists
    $existingStock = YearbookStock::where('yearbook_platform_id', $platform->id)->first();
    
    if (!$existingStock) {
        // Create new stock record
        YearbookStock::create([
            'yearbook_platform_id' => $platform->id,
            'initial_stock' => 100,  // Default initial stock
            'available_stock' => 100, // Default available stock
            'price' => 2300.00, // Default price (₱2,300)
        ]);
        
        $count++;
        echo "Created stock record for platform: {$platform->name} ({$platform->year})\n";
    }
}

echo "Fix completed! Created {$count} new stock records.\n";
echo "You should now be able to access the yearbook platforms page without errors.\n";
