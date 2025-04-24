<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new profile fields after existing ones like 'role_name'
            $table->string('middle_name')->nullable()->after('last_name');
            $table->string('suffix')->nullable()->after('middle_name');
            $table->string('gender')->nullable()->after('suffix'); // Consider enum or specific values later if needed
            $table->date('birthdate')->nullable()->after('gender');
            // 'contact_number' might already exist in yearbook_profiles, decide source of truth
            // Add here if it's the primary contact, make nullable
            $table->string('contact_number')->nullable()->after('birthdate');
            $table->text('address_line')->nullable()->after('contact_number'); // For House No, Street, Brgy
            $table->string('city_province')->nullable()->after('address_line'); // For City, Province
            // Add indexes if these fields will be frequently searched
            $table->index('birthdate');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop columns in reverse order of creation (best practice)
            $columnsToDrop = [
                'city_province',
                'address_line',
                'contact_number',
                'birthdate',
                'gender',
                'suffix',
                'middle_name'
            ];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};