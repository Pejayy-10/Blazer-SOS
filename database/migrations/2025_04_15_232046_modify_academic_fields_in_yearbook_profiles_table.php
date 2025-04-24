<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Ensure the class name matches the filename convention
// e.g., ModifyAcademicFieldsInYearbookProfilesTable
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the table exists before attempting to modify it
        if (Schema::hasTable('yearbook_profiles')) {
            Schema::table('yearbook_profiles', function (Blueprint $table) {

                // --- Add New Foreign Key Columns ---
                // Check if columns don't already exist before adding

                // College ID (references colleges table)
                if (!Schema::hasColumn('yearbook_profiles', 'college_id')) {
                    $table->foreignId('college_id')
                          ->nullable() // Allow null initially or if college is deleted
                          ->after('user_id') // Place it logically in the table
                          ->constrained('colleges') // Links to 'id' on 'colleges' table
                          ->onDelete('set null'); // Set to NULL if referenced college is deleted
                }

                // Course ID (references courses table)
                if (!Schema::hasColumn('yearbook_profiles', 'course_id')) {
                     $table->foreignId('course_id')
                           ->nullable()
                           ->after('college_id') // Place after college_id
                           ->constrained('courses') // Links to 'id' on 'courses' table
                           ->onDelete('set null');
                 }

                // --- Optional: Major ID ---
                // if (!Schema::hasColumn('yearbook_profiles', 'major_id')) {
                //     $table->foreignId('major_id')
                //           ->nullable()
                //           ->after('course_id') // Place after course_id
                //           ->constrained('majors') // Links to 'id' on 'majors' table
                //           ->onDelete('set null');
                // }


                // --- Drop Old String Columns ---
                // Check if columns exist before dropping

                if (Schema::hasColumn('yearbook_profiles', 'college')) {
                    $table->dropColumn('college');
                }
                if (Schema::hasColumn('yearbook_profiles', 'course')) {
                    $table->dropColumn('course');
                }
                // Decide if you want to keep 'year_and_section' as a separate text input
                // or if it should be part of the Course/Major selection.
                // If keeping, don't drop it. If replacing, uncomment below:
                // if (Schema::hasColumn('yearbook_profiles', 'year_and_section')) {
                //    $table->dropColumn('year_and_section');
                // }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         // Check if the table exists before attempting to modify it
        if (Schema::hasTable('yearbook_profiles')) {
            Schema::table('yearbook_profiles', function (Blueprint $table) {

                // --- Drop New Foreign Keys and Columns ---
                // Important: Drop foreign key constraints BEFORE dropping the columns

                // Optional Major
                // if (Schema::hasColumn('yearbook_profiles', 'major_id')) {
                //     $table->dropForeign(['major_id']); // Drop constraint first
                //     $table->dropColumn('major_id');    // Then drop column
                // }

                 if (Schema::hasColumn('yearbook_profiles', 'course_id')) {
                    $table->dropForeign(['course_id']);
                    $table->dropColumn('course_id');
                 }

                 if (Schema::hasColumn('yearbook_profiles', 'college_id')) {
                    $table->dropForeign(['college_id']);
                    $table->dropColumn('college_id');
                 }

                 // --- Add Back Old String Columns ---
                 // Check if columns don't already exist before adding back

                 if (!Schema::hasColumn('yearbook_profiles', 'college')) {
                    // Place after user_id if possible, otherwise just add
                    $afterColumn = Schema::hasColumn('yearbook_profiles', 'user_id') ? 'user_id' : null;
                    $table->string('college')->nullable()->after($afterColumn);
                 }
                 if (!Schema::hasColumn('yearbook_profiles', 'course')) {
                      // Place after college if possible, otherwise just add
                     $afterColumn = Schema::hasColumn('yearbook_profiles', 'college') ? 'college' : (Schema::hasColumn('yearbook_profiles', 'user_id') ? 'user_id' : null);
                     $table->string('course')->nullable()->after($afterColumn);
                 }
                 // if (!Schema::hasColumn('yearbook_profiles', 'year_and_section')) {
                 //    $table->string('year_and_section')->nullable();
                 // }

            });
        }
    }
};