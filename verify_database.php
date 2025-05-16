<?php

require __DIR__.'/vendor/autoload.php';

try {
    $app = require_once __DIR__.'/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    print("=== Database Structure Verification ===\n\n");

    // Tables required for the application to function properly
    $requiredTables = [
        // Core Laravel tables
        'users',
        'migrations',
        'failed_jobs',
        'jobs',
        'sessions',
        'cache',
        'personal_access_tokens',
        
        // Application specific tables
        'role_names',
        'yearbook_platforms',
        'yearbook_stocks',
        'yearbook_subscriptions',
        'yearbook_profiles',
        'yearbook_photos',
        'colleges',
        'courses',
        'majors',
        'bulletins',
        'staff_invitations',
        'settings',
        'countries',
        'cities'
    ];

    print("Checking if all required tables exist:\n");
    $missingTables = [];
    $existingTables = [];

    foreach ($requiredTables as $table) {
        $exists = \Illuminate\Support\Facades\Schema::hasTable($table);
        $status = $exists ? "✓ EXISTS" : "✕ MISSING";
        print("{$table}: {$status}\n");
        
        if (!$exists) {
            $missingTables[] = $table;
        } else {
            $existingTables[] = $table;
        }
    }

    if (count($missingTables) > 0) {
        print("\n⚠️ Warning: The following tables are still missing:\n");
        foreach ($missingTables as $table) {
            print("- {$table}\n");
        }
        print("\nPlease create these tables to ensure the application works correctly.\n");
    } else {
        print("\n✓ All required tables exist in the database!\n");
    }

    // Check key relationships in the database
    print("\n=== Key Relationships Check ===\n");
    
    // Check yearbook_platforms has records
    $platformCount = \DB::table('yearbook_platforms')->count();
    print("Yearbook Platforms: {$platformCount} records\n");
    
    // Check yearbook_stocks has records
    $stockCount = \DB::table('yearbook_stocks')->count();
    print("Yearbook Stocks: {$stockCount} records\n");
    
    // Check relationship between platforms and stocks
    $platformsWithStocks = \DB::table('yearbook_platforms')
        ->join('yearbook_stocks', 'yearbook_platforms.id', '=', 'yearbook_stocks.yearbook_platform_id')
        ->count();
    print("Platforms with stocks: {$platformsWithStocks} records\n");
    
    // Check user roles
    $userRoles = \DB::table('users')
        ->join('role_names', 'users.role_name_id', '=', 'role_names.id')
        ->select('role_names.name', \DB::raw('count(*) as count'))
        ->groupBy('role_names.name')
        ->get();
    
    print("\nUser roles distribution:\n");
    foreach ($userRoles as $role) {
        print("- {$role->name}: {$role->count} users\n");
    }
    
    print("\n=== Database Check Complete ===\n");
} catch (Exception $e) {
    print("Error: " . $e->getMessage() . "\n");
    print("File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n");
}
