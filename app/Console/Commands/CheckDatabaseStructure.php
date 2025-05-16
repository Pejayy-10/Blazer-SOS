<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckDatabaseStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:check-structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if all required tables exist in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking database structure...');
        
        // Define required tables
        $requiredTables = [
            'users',
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'failed_jobs',
            'yearbook_platforms',
            'yearbook_stocks',
            'yearbook_subscriptions',
            // Add other required tables here
        ];
        
        $existingTables = [];
        $missingTables = [];
        
        // Check each table
        foreach ($requiredTables as $table) {
            if (Schema::hasTable($table)) {
                $existingTables[] = $table;
                
                // Count records in the table
                $count = DB::table($table)->count();
                $this->info("✓ Table '{$table}' exists with {$count} records.");
            } else {
                $missingTables[] = $table;
                $this->error("✗ Table '{$table}' does not exist!");
            }
        }
        
        // Summary
        $this->info('');
        $this->info('Summary:');
        $this->info('- Total required tables: ' . count($requiredTables));
        $this->info('- Existing tables: ' . count($existingTables));
        $this->info('- Missing tables: ' . count($missingTables));
        
        if (count($missingTables) > 0) {
            $this->error('Missing tables: ' . implode(', ', $missingTables));
            return 1;
        } else {
            $this->info('All required tables exist! Database structure is complete.');
            return 0;
        }
    }
}
