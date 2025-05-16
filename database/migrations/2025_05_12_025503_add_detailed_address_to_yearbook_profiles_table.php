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
    $table->string('street_address')->nullable()->after('address'); // Or after birth_date if address is removed
    $table->string('city')->nullable()->after('street_address');
    $table->string('province_state')->nullable()->after('city');
    $table->string('zip_code')->nullable()->after('province_state');
    $table->string('country')->nullable()->default('Philippines')->after('zip_code'); // Optional default

    // Drop old single address field if you're completely replacing it
    // if (Schema::hasColumn('yearbook_profiles', 'address')) {
    //     $table->dropColumn('address');
    // }
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yearbook_profiles', function (Blueprint $table) {
    // Drop new columns
    $columnsToDrop = ['country', 'zip_code', 'province_state', 'city', 'street_address'];
    foreach ($columnsToDrop as $column) {
        if (Schema::hasColumn('yearbook_profiles', $column)) {
            $table->dropColumn($column);
        }
    }
    // Add back old 'address' column if it was dropped
    // if (!Schema::hasColumn('yearbook_profiles', 'address')) {
    //     $table->text('address')->nullable();
    // }
});
    }
};
