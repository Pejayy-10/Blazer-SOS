<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('yearbook_profiles', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('yearbook_profiles', 'major_id')) {
                // Add after 'course_id' for logical placement
                $table->foreignId('major_id')
                      ->nullable() // Make it nullable since it's optional
                      ->after('course_id')
                      ->constrained('majors') // Constrain to majors table
                      ->onDelete('set null'); // Set null if major is deleted
            }
        });
    }

    public function down(): void
    {
        Schema::table('yearbook_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('yearbook_profiles', 'major_id')) {
                $table->dropForeign(['major_id']); // Drop constraint first
                $table->dropColumn('major_id');
            }
        });
    }
};