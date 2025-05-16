<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if tables exist and mark migrations as completed
        $batch = DB::table('migrations')->max('batch') + 1;
        
        // Check if failed_jobs table exists and mark its migration as completed
        if (Schema::hasTable('failed_jobs')) {
            DB::table('migrations')->updateOrInsert(
                ['migration' => '2025_04_15_190835_create_failed_jobs_table'],
                ['batch' => $batch]
            );
        }
        
        // Check if cache table exists and mark its migration as completed
        if (Schema::hasTable('cache')) {
            DB::table('migrations')->updateOrInsert(
                ['migration' => '2025_04_15_190841_create_cache_table'],
                ['batch' => $batch]
            );
        }
        
        // Check if personal_access_tokens table exists and mark its migration as completed
        if (Schema::hasTable('personal_access_tokens')) {
            DB::table('migrations')->updateOrInsert(
                ['migration' => '2025_04_15_191327_create_personal_access_tokens_table'],
                ['batch' => $batch]
            );
        }
        
        // Check if users table has middle_name column and mark the migration as completed
        if (Schema::hasColumn('users', 'middle_name')) {
            DB::table('migrations')->updateOrInsert(
                ['migration' => '2025_05_15_161959_add_middle_name_to_users'],
                ['batch' => $batch]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse as we're just marking migrations as completed
    }
};
