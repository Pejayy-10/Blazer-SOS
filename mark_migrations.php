<?php

require __DIR__.'/vendor/autoload.php';

try {
    $app = require_once __DIR__.'/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "=== Starting Migration Status Check ===\n\n";

    // List of pending migrations to mark as complete
    $pendingMigrations = [
        '2025_04_15_190835_create_failed_jobs_table',
        '2025_04_15_190841_create_cache_table', 
        '2025_04_15_191327_create_personal_access_tokens_table',
        '2025_05_15_161959_add_middle_name_to_users',
        '2025_05_17_000000_mark_completed_migrations'
    ];

    // Get current migrations status
    echo "Current Migration Status:\n";
    $currentMigrations = DB::table('migrations')->get();
    $completedMigrations = $currentMigrations->pluck('migration')->toArray();
    
    echo "Total migrations in database: " . count($completedMigrations) . "\n\n";
    
    // Mark each migration as completed
    echo "Marking pending migrations as completed...\n";
    foreach ($pendingMigrations as $migration) {
        // Check if the migration is already in the table
        $exists = in_array($migration, $completedMigrations);
        
        if (!$exists) {
            DB::table('migrations')->insert([
                'migration' => $migration,
                'batch' => 30
            ]);
            echo "✓ Marked {$migration} as completed\n";
        } else {
            echo "● {$migration} is already marked as completed\n";
        }
    }

    // Verify the tables exist in the database
    $tables = [
        'failed_jobs',
        'cache',
        'personal_access_tokens',
        'sessions',
        'jobs',
        'yearbook_platforms',
        'yearbook_stocks',
        'yearbook_subscriptions',
        'users',
        'role_names'
    ];

    echo "\nChecking if important tables exist:\n";
    $missingTables = [];
    foreach ($tables as $table) {
        $exists = DB::getSchemaBuilder()->hasTable($table);
        $status = $exists ? "EXISTS" : "MISSING";
        echo "{$table}: " . $status . "\n";
        
        if (!$exists) {
            $missingTables[] = $table;
        }
    }

    if (count($missingTables) > 0) {
        echo "\n⚠️ Warning: The following tables are still missing:\n";
        foreach ($missingTables as $table) {
            echo "- {$table}\n";
        }
    } else {
        echo "\n✓ All required tables exist in the database!\n";
    }

    echo "\n=== Migration Status Check Complete ===\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
}
