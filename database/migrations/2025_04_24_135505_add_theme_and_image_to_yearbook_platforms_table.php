<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('yearbook_platforms', function (Blueprint $table) {
            $table->string('theme_title')->nullable()->after('name');
            $table->string('background_image_path')->nullable()->after('theme_title');
        });
    }
    public function down(): void {
        Schema::table('yearbook_platforms', function (Blueprint $table) {
            if (Schema::hasColumn('yearbook_platforms', 'background_image_path')) {
                $table->dropColumn('background_image_path');
            }
             if (Schema::hasColumn('yearbook_platforms', 'theme_title')) {
                $table->dropColumn('theme_title');
            }
        });
    }
};