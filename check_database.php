<?php

// Bootstrap Laravel
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get list of tables
$tables = DB::select('SHOW TABLES');

echo "========== DATABASE TABLES ==========\n";
foreach ($tables as $table) {
    $tableName = array_values(get_object_vars($table))[0];
    $count = DB::table($tableName)->count();
    echo "- {$tableName} ({$count} records)\n";
}
echo "===================================\n";

// Check specific tables we need
$requiredTables = [
    'users',
    'sessions',
    'yearbook_platforms',
    'yearbook_stocks',
    'yearbook_subscriptions',
];

echo "\n========== STATUS CHECK ==========\n";
$allExist = true;
foreach ($requiredTables as $table) {
    if (Schema::hasTable($table)) {
        echo "✓ Table '{$table}' exists.\n";
    } else {
        echo "✗ Table '{$table}' is MISSING!\n";
        $allExist = false;
    }
}

echo "\nVerdict: " . ($allExist ? "All required tables exist!" : "Some tables are missing!") . "\n";
