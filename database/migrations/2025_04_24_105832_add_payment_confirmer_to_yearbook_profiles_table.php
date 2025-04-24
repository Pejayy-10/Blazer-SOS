<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('yearbook_profiles', function (Blueprint $table) {
             // Foreign key to the users table (for the admin who confirmed)
             // Place it after payment status or paid_at for logical grouping
            $table->foreignId('payment_confirmed_by')
                  ->nullable() // Null if payment not confirmed or if confirmer deleted
                  ->after('paid_at') // Adjust placement as needed
                  ->constrained('users') // Links to 'id' on 'users' table
                  ->onDelete('set null'); // Set null if the admin user is deleted
        });
    }

    public function down(): void
    {
        Schema::table('yearbook_profiles', function (Blueprint $table) {
             if (Schema::hasColumn('yearbook_profiles', 'payment_confirmed_by')) {
                 $table->dropForeign(['payment_confirmed_by']);
                 $table->dropColumn('payment_confirmed_by');
             }
        });
    }
};