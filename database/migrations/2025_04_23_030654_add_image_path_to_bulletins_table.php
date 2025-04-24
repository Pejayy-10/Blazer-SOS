<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bulletins', function (Blueprint $table) {
             // Add after content column, for example
            $table->string('image_path')->nullable()->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('bulletins', function (Blueprint $table) {
             if (Schema::hasColumn('bulletins', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};