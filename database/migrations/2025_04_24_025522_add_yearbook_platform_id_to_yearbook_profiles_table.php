<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('yearbook_profiles', function (Blueprint $table) {
             // Add after college_id or course_id for logical placement
            $table->foreignId('yearbook_platform_id')
                  ->nullable() // Allow null initially
                  ->after('course_id') // Adjust placement as needed
                  ->constrained('yearbook_platforms') // Links to 'id' on 'yearbook_platforms'
                  ->onDelete('set null'); // Or restrict/cascade based on requirements
        });
    }

    public function down(): void
    {
        Schema::table('yearbook_profiles', function (Blueprint $table) {
             if (Schema::hasColumn('yearbook_profiles', 'yearbook_platform_id')) {
                 // Ensure constraint name matches if default wasn't used
                 // Default is usually: tablename_columnname_foreign
                 $table->dropForeign(['yearbook_platform_id']); // Or $table->dropForeign('yearbook_profiles_yearbook_platform_id_foreign');
                 $table->dropColumn('yearbook_platform_id');
             }
        });
    }
};