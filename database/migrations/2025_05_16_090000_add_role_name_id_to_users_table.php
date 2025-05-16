<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add role_name_id to users table
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add role_name_id to establish a relationship with the role_names table
            $table->foreignId('role_name_id')->nullable()->after('role_name')
                  ->constrained('role_names')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropConstrainedForeignId('role_name_id');
        });
    }
};
