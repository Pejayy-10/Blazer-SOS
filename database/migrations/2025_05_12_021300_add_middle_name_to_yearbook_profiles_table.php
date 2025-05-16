<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('yearbook_profiles', function (Blueprint $table) {
    // Add after first_name or nickname
    $table->string('middle_name')->nullable()->after('nickname');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yearbook_profiles', function (Blueprint $table) {
    if (Schema::hasColumn('yearbook_profiles', 'middle_name')) {
        $table->dropColumn('middle_name');
    }
});
    }
};
